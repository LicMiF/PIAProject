const now = new Date();
const currentDateString = now.toISOString().split('T')[0];

document.getElementById('datepicker').value = currentDateString;

document.getElementById('datepicker').min = currentDateString;

const hoursSelect = document.getElementById('hourSelect');
for (let i = 0; i < 24; i++) {
    const option = document.createElement('option');
    option.value = i < 10 ? '0' + i : '' + i;
    option.textContent = option.value;
    hoursSelect.appendChild(option);
}

const minutesSelect = document.getElementById('minuteSelect');
for (let i = 0; i < 60; i ++) {
    const option = document.createElement('option');
    option.value = i < 10 ? '0' + i : '' + i;
    option.textContent = option.value;
    minutesSelect.appendChild(option);
}