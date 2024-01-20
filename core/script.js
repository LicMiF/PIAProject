function send(id)
{
    window.location="send.php?id="+id;
}

function approve(id)
{
    window.location="approve.php?id="+id;
}



function showForm(userType) {
    var studentBtn = document.getElementById("studentBtn");
    var mentorBtn = document.getElementById("mentorBtn");
    var studentForm = document.getElementById("studentForm");
    var mentorForm = document.getElementById("mentorForm");

    if (userType === "student") {
        studentForm.classList.add("show-form");
        mentorForm.classList.remove("show-form");
        studentBtn.style.backgroundColor = "#04473e";
        mentorBtn.style.backgroundColor = "#fff";


        studentBtn.style.color = "#fff";
        mentorBtn.style.color = "#04473e";

    } else {
        mentorForm.classList.add("show-form");
        studentForm.classList.remove("show-form");
        mentorBtn.style.backgroundColor = "#04473e";
        studentBtn.style.backgroundColor = "#fff"

        studentBtn.style.color = "#04473e";
        mentorBtn.style.color = "#fff";
    }
}
