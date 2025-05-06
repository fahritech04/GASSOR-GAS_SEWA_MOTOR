// Get all tab buttons
const tabLinks = document.querySelectorAll(".tab-link");

// Add click event listener to each button
tabLinks.forEach((button) => {
    button.addEventListener("click", () => {
        // Get the target tab id from the data attribute
        const targetTab = button.getAttribute("data-target-tab");
        // Hide all tab contents
        document.querySelectorAll(".tab-content").forEach((content) => {
            content.classList.add("hidden");
        });

        // Show the target tab content
        document.querySelector(targetTab).classList.toggle("hidden");
    });
});

// Ambil elemen harga pembayaran
const downPaymentPrice =
    document.getElementById("downPaymentPrice").textContent;
const fullPaymentPrice =
    document.getElementById("fullPaymentPrice").textContent;
const priceElement = document.getElementById("price");
const paymentOptions = document.querySelectorAll(
    'input[name="payment_method"]'
);

// Ambil elemen durasi (per hari)
const durationInput = document.getElementById("Duration");
let duration = 1;
if (durationInput) {
    duration = parseInt(durationInput.value, 10) || 1;
}

// Fungsi untuk update harga berdasarkan metode pembayaran dan durasi per hari
function updatePrice() {
    const selectedPayment = document.querySelector(
        'input[name="payment_method"]:checked'
    ).value;

    let price = 0;
    if (selectedPayment === "down_payment") {
        price = parseInt(downPaymentPrice.replace(/\D/g, ""), 10) * duration;
        priceElement.innerHTML = `Rp ${price.toLocaleString(
            "id-ID"
        )} <span class="text-sm text-gassor-grey font-normal">/hari</span>`;
    } else if (selectedPayment === "full_payment") {
        price = parseInt(fullPaymentPrice.replace(/\D/g, ""), 10) * duration;
        priceElement.innerHTML = `Rp ${price.toLocaleString(
            "id-ID"
        )} <span class="text-sm text-gassor-grey font-normal">/hari</span>`;
    }
}

// Event listener untuk radio button pembayaran
paymentOptions.forEach((option) => {
    option.addEventListener("change", updatePrice);
});

// Jika ada input durasi, update harga saat berubah (walau default 1 hari)
if (durationInput) {
    durationInput.addEventListener("input", () => {
        duration = parseInt(durationInput.value, 10) || 1;
        updatePrice();
    });
    durationInput.addEventListener("blur", () => {
        duration = parseInt(durationInput.value, 10) || 1;
        updatePrice();
    });
}

// Trigger price update on page load
updatePrice();
