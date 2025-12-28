const guard = document.getElementById("guard")
const postList = document.getElementById("postList")
const kpi = document.getElementById("kpi")
const logout = document.getElementById("logout")

function getSession(){
  return JSON.parse(localStorage.getItem("lf_session") || "null")
}

function seedPostsIfEmpty(){
  const existing = JSON.parse(localStorage.getItem("lf_posts") || "[]")
  if(existing.length) return existing

  const seed = [
    { id: 1, type: "Lost", title: "Laptop Charger", location: "AIUB Library", date: "2025-12-14", by: "user1@mail.com", status: "Pending" },
    { id: 2, type: "Found", title: "Gold Ring", location: "Gulshan 1", date: "2025-12-13", by: "user2@mail.com", status: "Pending" },
    { id: 3, type: "Lost", title: "Student ID Card", location: "Badda", date: "2025-12-12", by: "user3@mail.com", status: "Pending" },
    { id: 4, type: "Found", title: "Headphones", location: "Kuril", date: "2025-12-11", by: "user4@mail.com", status: "Approved" }
  ]
  localStorage.setItem("lf_posts", JSON.stringify(seed))
  return seed
}

function savePosts(posts){
  localStorage.setItem("lf_posts", JSON.stringify(posts))
}

function pill(text){
  const el = document.createElement("div")
  el.className = "pill"
  el.textContent = text
  return el
}

function renderKpi(posts){
  kpi.innerHTML = ""
  const total = posts.length
  const pending = posts.filter(p=>p.status==="Pending").length
  const approved = posts.filter(p=>p.status==="Approved").length
  const rejected = posts.filter(p=>p.status==="Rejected").length
  kpi.appendChild(pill(`Total: ${total}`))
  kpi.appendChild(pill(`Pending: ${pending}`))
  kpi.appendChild(pill(`Approved: ${approved}`))
  kpi.appendChild(pill(`Rejected: ${rejected}`))
}

function row(post){
  const wrap = document.createElement("div")
  wrap.className = "card"
  wrap.style.marginBottom = "10px"
  wrap.innerHTML = `
    <div class="post">
      <div class="thumb">Image</div>
      <div style="flex:1">
        <h3>${post.title} <span class="badge">${post.type}</span> <span class="badge">${post.status}</span></h3>
        <div class="meta">
          <span>📍 ${post.location}</span>
          <span>🗓️ ${post.date}</span>
          <span>👤 ${post.by}</span>
          <span>#${post.id}</span>
        </div>
        <div class="post-actions">
          <button class="btn small" data-act="approve" data-id="${post.id}">Approve</button>
          <button class="btn small danger" data-act="reject" data-id="${post.id}">Reject</button>
          <button class="btn small ghost" data-act="review" data-id="${post.id}">Mark Review</button>
        </div>
      </div>
    </div>
  `
  return wrap
}

function render(posts){
  postList.innerHTML = ""
  posts.forEach(p => postList.appendChild(row(p)))
  renderKpi(posts)
}

function updateStatus(id, status){
  const posts = JSON.parse(localStorage.getItem("lf_posts") || "[]")
  const idx = posts.findIndex(p => String(p.id) === String(id))
  if(idx === -1) return
  posts[idx].status = status
  savePosts(posts)
  render(posts)
}

function guardAccess(){
  const s = getSession()
  if(!s || s.role !== "admin"){
    guard.textContent = "Access denied. Please login as Admin."
    setTimeout(()=>{ window.location.href = "adminLogin.html" }, 900)
    return false
  }
  guard.textContent = ""
  return true
}

postList.addEventListener("click", (e)=>{
  const btn = e.target.closest("button")
  if(!btn) return
  const act = btn.getAttribute("data-act")
  const id = btn.getAttribute("data-id")
  if(act === "approve") updateStatus(id, "Approved")
  if(act === "reject") updateStatus(id, "Rejected")
  if(act === "review") updateStatus(id, "Pending")
})

logout.addEventListener("click", (e)=>{
  e.preventDefault()
  localStorage.removeItem("lf_session")
  window.location.href = "homePage.html"
})

if(guardAccess()){
  const posts = seedPostsIfEmpty()
  render(posts)
}
