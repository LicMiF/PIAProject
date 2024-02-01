function send(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('send.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        console.log('Request successful:', data);
        // Handle the response if needed
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function approve(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('approve.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function refuse(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('refuse.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function deleteComment(id) {

    const formData = new FormData();
    formData.append('id', id);

    fetch('deleteComment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}



function cancelClass(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('cancelClass.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}



function cancelClassCheck(id)
{
    if(window.confirm("Da li ste sigurni da želite da otkažete čas?"))
        cancelClass(id);
}

function deleteNotification(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('deleteNotification.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function activateProfile(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('activateProfile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function removeProfile(id,userType) {


    if(!window.confirm("Da li ste sigurni da želite da obrišete nalog?"))
        return;
    const formData = new FormData();
    formData.append('id', id);
    formData.append('userType', userType);
    fetch('removeProfile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}

function handleLike(commentId,senderId) {
    const formData = new FormData();
    formData.append('commentId', commentId);
    formData.append('senderId', senderId);
    fetch('updateLikes.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}


function handleDislike(commentId,senderId) {
    const formData = new FormData();
    formData.append('commentId', commentId);
    formData.append('senderId', senderId);
    fetch('updateDislikes.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}


async function handleReadMessages(Id) {
    try {
        const formData = new FormData();
        formData.append('Id', Id);
        const response = await fetch('markAsReadMessages.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        // Do not try this at home, this is only allowed one day before presenting the site :(
        const data = await response.text();

        var url = './chat.php?userId=' + Id;
        window.location.href = url;


    } catch (error) {
        console.error('Error during POST request:', error);
    }
}


function upgradeToAdmin(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('upgradeUserToAdmin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}


function activateProfile(id) {
    const formData = new FormData();
    formData.append('id', id);
    fetch('activateProfile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error during POST request:', error);
});
}


function updateViewedNotifications()
{
        var notificationCircle = document.getElementById('notificationCount');
        if (notificationCircle) {
            notificationCircle.style.display = 'none';
        }
}


function updateViewedNotificationsMessages(count)
{
        var notificationCircle = document.getElementById('notificationCount1');
        if(count>0)
        {
            if (notificationCircle) {
                notificationCircle.innerHTML= count;
            }
        }
        else
        {
            if (notificationCircle) {
                notificationCircle.style.display = 'none';
            }
        }
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



    function viewProfile(id, userType)
    {

        var postData = {
            profileId: id,
            userType: userType,
        };

        var urlEncodedData = new URLSearchParams(postData).toString();

        var phpScriptUrl = 'profile.php';

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = phpScriptUrl;

        for (var key in postData) {
            if (postData.hasOwnProperty(key)) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = postData[key];
                form.appendChild(input);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }


    function editProfile(id, userType)
    {

        var postData = {
            profileId: id,
            userType: userType,
        };

        var urlEncodedData = new URLSearchParams(postData).toString();

        var phpScriptUrl = 'editProfile.php';

        var form = document.createElement('form');
        form.method = 'POST';
        form.action = phpScriptUrl;

        for (var key in postData) {
            if (postData.hasOwnProperty(key)) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = postData[key];
                form.appendChild(input);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }



    function maintainScrollPosition()
    {
        // Get the current scroll position
        var scrollPosition = window.scrollY

        // Store the scroll position in sessionStorage
        sessionStorage.setItem('scrollPosition', scrollPosition);

        // To retrieve the scroll position after a page refresh
        var storedScrollPosition = sessionStorage.getItem('scrollPosition');

        // If there's a stored scroll position, scroll to that position
        if (storedScrollPosition !== null) {
            window.scrollTo(0, storedScrollPosition);
        }
    }


    function generateStarRatingDuplicate(rating) {
        const maxRating=5;
        const fullStars = '&#9733;'.repeat(Math.floor(rating));
        const halfStar = (rating % 1 !== 0) ? '&#9733;' : '';
        const emptyStars = '&#9734;'.repeat(Math.floor(maxRating - rating));
        return `${fullStars}${halfStar}${emptyStars}`;
    }
