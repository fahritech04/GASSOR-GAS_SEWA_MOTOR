// Tidak ada tab pembayaran, hanya satu metode
const priceElement = document.getElementById("price");
const fullPaymentPrice =
    document.getElementById("fullPaymentPrice").textContent;

function updatePrice() {
    priceElement.innerHTML = fullPaymentPrice;
}

// Trigger price update on page load
updatePrice();
