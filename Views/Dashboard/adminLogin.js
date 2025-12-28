const u = document.getElementById("username")
const p = document.getElementById("password")
const err = document.getElementById("err")
const ok = document.getElementById("ok")
const btn = document.getElementById("btnAdmin")

function setMsg(e, o){
  err.textContent = e || ""
  ok.textContent = o || ""
}

function saveSession(data){
  localStorage.setItem("lf_session", JSON.stringify(data))
}

function validate(){
  if(u.value.trim().length === 0) return setMsg("Username is required.", ""), false
  if(p.value.length < 6) return setMsg("Password must be at least 6 characters.", ""), false
  return true
}

function login(){
  setMsg("", "")
  if(!validate()) return

  const user = u.value.trim()
  const pass = p.value

  if(user !== "admin" || pass !== "admin123") return setMsg("Invalid admin credentials.", ""), null

  saveSession({ role: "admin", username: "admin" })
  setMsg("", "Admin login successful! Redirecting...")
  setTimeout(()=>{ window.location.href = "adminDashboard.html" }, 600)
}

btn.addEventListener("click", login)
