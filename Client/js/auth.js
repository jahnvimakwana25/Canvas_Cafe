const user_crendetial = JSON.parse(localStorage.getItem("user_Cred"));
let uname = document.getElementById('customer_name');
let lbtn = document.getElementById('login_details');
let logbtn = document.getElementById('logOut_link');

if (user_crendetial) {
    uname.innerHTML = `Welcome, ${user_crendetial.first_name}`;
    uname.style.display = 'block';
    logbtn.style.display = 'block';
    lbtn.style.display = 'none';
}
else {
    uname.innerHTML = '';
    uname.style.display = 'none';
    logbtn.style.display = 'none';
    lbtn.style.display = 'block';
}



// Lougout;

const logOut = () => {
   localStorage.removeItem('user_Cred');
   window.location.href = "index.php"
}