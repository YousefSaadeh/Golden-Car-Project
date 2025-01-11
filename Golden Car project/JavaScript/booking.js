// booking.js - Logic for booking page

let selectedCarType = "";
let selectedCar = "";
let pickupDate = "";
let returnDate = "";

const cars = {
    sedan: [
        { name: "Toyota Camry", price: 30 },
        { name: "Honda Accord", price: 30 },
        { name: "BMW 3 Series", price: 45 },
        { name: "Mercedes-Benz E-Class", price: 50 },
    ],
    suv: [
        { name: "Ford F-150", price: 50 },
        { name: "Chevrolet Silverado", price: 50 },
        { name: "Toyota Land Cruiser", price: 50 },
        { name: "Nissan Patrol", price: 50 },
    ],
    motorcycle: [
        { name: "Harley Davidson Sportster", price: 20 },
        { name: "Yamaha YZF-R6", price: 25 },
        { name: "Suzuki GSX-R1000", price: 30 },
        { name: "Kawasaki Ninja H2", price: 35 },
    ],
};

function selectCarType(type) {
    selectedCarType = type;
    document.getElementById("step-1").style.display = "none";
    document.getElementById("step-2").style.display = "block";

    const carListDiv = document.getElementById("car-list");
    carListDiv.innerHTML = "";

    cars[type].forEach((car, index) => {
        const carDiv = document.createElement("div");
        carDiv.className = "car-option";
        carDiv.innerHTML = `
            <p>${car.name} - ${car.price} JD/day</p>
            <button onclick="selectCar(${index})">Select</button>
        `;
        carListDiv.appendChild(carDiv);
    });
}

function selectCar(index) {
    selectedCar = cars[selectedCarType][index];
    document.getElementById("step-2").style.display = "none";
    document.getElementById("step-3").style.display = "block";
}

function goBack(step) {
    document.querySelectorAll(".step").forEach(stepDiv => {
        stepDiv.style.display = "none";
    });
    document.getElementById(`step-${step}`).style.display = "block";
}

function confirmBooking() {
    pickupDate = document.getElementById("pickup-date").value;
    returnDate = document.getElementById("return-date").value;

    if (!pickupDate || !returnDate) {
        alert("Please select both pick-up and return dates.");
        return;
    }

    const days = calculateDays(pickupDate, returnDate);
    const totalCost = days * selectedCar.price;

    const summaryDiv = document.getElementById("booking-summary");
    summaryDiv.innerHTML = `
        <p>Car: ${selectedCar.name}</p>
        <p>Pick-up Date: ${pickupDate}</p>
        <p>Return Date: ${returnDate}</p>
        <p>Total Days: ${days}</p>
        <p>Total Cost: ${totalCost} JD</p>
    `;

    document.getElementById("step-3").style.display = "none";
    document.getElementById("step-4").style.display = "block";
}

function calculateDays(start, end) {
    const startDate = new Date(start);
    const endDate = new Date(end);
    const difference = endDate - startDate;
    return Math.ceil(difference / (1000 * 60 * 60 * 24));
}

function finalizeBooking() {
    const bookingData = new URLSearchParams();
    bookingData.append('car', selectedCar.name);
    bookingData.append('pickupDate', pickupDate);
    bookingData.append('returnDate', returnDate);
    bookingData.append('price', selectedCar.price);

    fetch("../HTML/process_booking.php", {
        method: "POST",
        body: bookingData,
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            try {
                const result = JSON.parse(data);
                if (result.success) {
                    alert("Booking confirmed! Thank you.");
                    window.location.href = "home.html";
                } else {
                    alert(`Error: ${result.error || "Unknown error"}`);
                }
            } catch (e) {
                console.error("Invalid response:", data);
                alert("An unexpected error occurred.");
            }
        })
        .catch(error => console.error("Error:", error));
}
