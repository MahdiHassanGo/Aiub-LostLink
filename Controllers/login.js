const email = document.getElementById("email")
const password = document.getElementById("password")
const err = document.getElementById("err")
const ok = document.getElementById("ok")
const btnLogin = document.getElementById("btnLogin")

function setMsg(e, o){
  err.textContent = e || ""
  ok.textContent = o || ""
}

function isEmail(v){
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)
}

function loadUsers(){
  return JSON.parse(localStorage.getItem("lf_users") || "[]")
}

function saveSession(data){
  localStorage.setItem("lf_session", JSON.stringify(data))
}

function validate(){
  const e = email.value.trim()
  const p = password.value

  if(e.length === 0) return setMsg("Email is required."), false
  if(!isEmail(e)) return setMsg("Enter a valid email."), false
  if(p.length < 6) return setMsg("Password must be at least 6 characters."), false

  return true
}

function login(){
  setMsg("", "")
  if(!validate()) return

  const users = loadUsers()
  const e = email.value.trim().toLowerCase()
  const p = password.value

  const found = users.find(u => u.email === e && u.password === p)
  if(!found) return setMsg("Invalid email or password."), null

  saveSession({ role: "user", email: found.email, name: found.name })
  setMsg("", "Login successful! Redirecting...")
  setTimeout(()=>{ window.location.href = "homePage.html" }, 600)
}

btnLogin.addEventListener("click", login)
email.addEventListener("blur", ()=>{ if(email.value.trim() && !isEmail(email.value.trim())) setMsg("Enter a valid email.", "") })
password.addEventListener("blur", ()=>{ if(password.value && password.value.length < 6) setMsg("Password must be at least 6 characters.", "") })
