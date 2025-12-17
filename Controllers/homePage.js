const lostSeed = [
  { title: "Black Wallet", location: "AIUB Campus", date: "2025-12-12", tag: "Lost" },
  { title: "Samsung Earbuds", location: "Kuril Bus Stop", date: "2025-12-10", tag: "Lost" },
  { title: "ID Card", location: "Banani", date: "2025-12-08", tag: "Lost" }
]

const foundSeed = [
  { title: "Keys (2 keys)", location: "Jamuna Future Park", date: "2025-12-13", tag: "Found" },
  { title: "Water Bottle", location: "Dhanmondi Lake", date: "2025-12-11", tag: "Found" },
  { title: "Backpack", location: "Uttara Sector 7", date: "2025-12-09", tag: "Found" }
]

function cardRow(item){
  const wrap = document.createElement("div")
  wrap.className = "card"
  wrap.style.marginBottom = "10px"
  wrap.innerHTML = `
    <div class="post">
      <div class="thumb">Image</div>
      <div style="flex:1">
        <h3>${item.title} <span class="badge">${item.tag}</span></h3>
        <div class="meta">
          <span>📍 ${item.location}</span>
          <span>🗓️ ${item.date}</span>
        </div>
        <button class="btn small">View</button>
      </div>
    </div>
  `
  return wrap
}

function render(){
  const lostList = document.getElementById("lostList")
  const foundList = document.getElementById("foundList")
  lostSeed.forEach(x => lostList.appendChild(cardRow(x)))
  foundSeed.forEach(x => foundList.appendChild(cardRow(x)))

  const kpi = document.getElementById("kpi")
  const users = JSON.parse(localStorage.getItem("lf_users") || "[]")
  const posts = JSON.parse(localStorage.getItem("lf_posts") || "[]")

  const pills = [
    { t: `Registered Users: ${users.length}` },
    { t: `Prototype Posts Saved: ${posts.length}` },
    { t: `Lost Seed: ${lostSeed.length}` },
    { t: `Found Seed: ${foundSeed.length}` }
  ]
  pills.forEach(p=>{
    const el = document.createElement("div")
    el.className = "pill"
    el.textContent = p.t
    kpi.appendChild(el)
  })

  const msg = document.getElementById("homeMsg")
  const session = JSON.parse(localStorage.getItem("lf_session") || "null")
  if(session && session.role){
    msg.textContent = `You are logged in as: ${session.role} (${session.email || session.username || "user"})`
  } else {
    msg.textContent = ""
  }
}

render()
