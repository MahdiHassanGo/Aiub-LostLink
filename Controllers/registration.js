const nameEl = document.getElementById("name")
const emailEl = document.getElementById("email")
const passEl = document.getElementById("password")
const confirmEl = document.getElementById("confirm")
const err = document.getElementById("err")
const ok = document.getElementById("ok")
const btnCreate = document.getElementById("btnCreate")

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

function saveUsers(users){
  localStorage.setItem("lf_users", JSON.stringify(users))
}

function validate(){
  const n = nameEl.value.trim()
  const e = emailEl.value.trim()
  const p = passEl.value
  const c = confirmEl.value

  if(n.length < 3) return setMsg("Name must be at least 3 characters.", ""), false
  if(e.length === 0) return setMsg("Email is required.", ""), false
  if(!isEmail(e)) return setMsg("Enter a valid email.", ""), false
  if(p.length < 6) return setMsg("Password must be at least 6 characters.", ""), false
  if(c !== p) return setMsg("Confirm password does not match.", ""), false

  return true
}

function register(){
  setMsg("", "")
  if(!validate()) return

  const users = loadUsers()
  const e = emailEl.value.trim().toLowerCase()

  if(users.some(u => u.email === e)) return setMsg("This email is already registered.", ""), null

  users.push({
    name: nameEl.value.trim(),
    email: e,
    password: passEl.value
  })

  saveUsers(users)
  setMsg("", "Registration successful! Redirecting to login...")
  setTimeout(()=>{ window.location.href = "login.html" }, 650)
}

btnCreate.addEventListener("click", register)
emailEl.addEventListener("blur", ()=>{ if(emailEl.value.trim() && !isEmail(emailEl.value.trim())) setMsg("Enter a valid email.", "") })
confirmEl.addEventListener("blur", ()=>{ if(confirmEl.value && confirmEl.value !== passEl.value) setMsg("Confirm password does not match.", "") })
