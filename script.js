const API_URL = "http://192.168.1.170/api";

async function get_sensor_data() {
    const response = await fetch(`${API_URL}/sensors.php`);
    const data = await response.json();
    return data.data;
}

async function get_user_data() {
    const response = await fetch(`${API_URL}/user.php`);
    const data = await response.json();
    return data.data;
}

async function get_token(form) {
    const response = await fetch(`${API_URL}/auth.php`, {
        method: "POST",
        body: form
    });
    const data = await response.json();
    return data.data;
}

async function check_session() {

    let user = await get_user_data();

    if(!user) {
        window.location.href = "login.html";
    }

    localStorage.setItem("user", JSON.stringify(user));

    return user;
}

async function get_history() {

    const response = await fetch(`${API_URL}/history.php`);
    const data = await response.json();
    return data.data;
}

async function get_configs() {
    
    const response = await fetch(`${API_URL}/config.php`);
    const data = await response.json();
    return data.data;
}

async function update_fan_speed(value) {
    const form = new URLSearchParams();
    form.append("name", "ac_fan_speed");
    form.append("value", value);
    const response = await fetch(`${API_URL}/sensors.php`, {
        method: "POST",
        body: form
    });
    const data = await response.json();
    return data.data;
}

async function update_temp(value) {
    const form = new URLSearchParams();
    form.append("name", "ac_temp");
    form.append("value", value);
    const response = await fetch(`${API_URL}/sensors.php`, {
        method: "POST",
        body: form
    });
    const data = await response.json();
    return data.data;
}

async function set_actuator_automatic(name, value=true) {

    const form = new URLSearchParams();
    form.append("name", name);
    form.append("value", value ? 1 : 0);

    const response = await fetch(`${API_URL}/set_automatic.php`, {
        method: "POST",
        body: form
    }); 
    const data = await response.json();
    return data.data;
}