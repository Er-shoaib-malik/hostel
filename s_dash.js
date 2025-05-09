const btn1 = document.querySelector(".apply_out");
const btn2 = document.querySelector(".check_status");
const btn3 = document.querySelector(".show_history");

const request = document.querySelector(".request_click");
const stat = document.querySelector(".status_click");
const history = document.querySelector(".history_click");

btn1.addEventListener("click", function () {
    request.style.display = "block";
    stat.style.display = "none";
    history.style.display = "none";
});

btn2.addEventListener("click", function () {
    request.style.display = "none";
    stat.style.display = "block";
    history.style.display = "none";
});

btn3.addEventListener("click", function () {
    request.style.display = "none";
    stat.style.display = "none";
    history.style.display = "block";
});


function logout() {
    sessionStorage.clear();
    alert('Logged Out');
    window.location.href = '/hostel/';
}
