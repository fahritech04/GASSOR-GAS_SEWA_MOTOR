const swiperTabs = new Swiper(".swiper", {
    slidesPerView: "auto",
    spaceBetween: 14,
    slidesOffsetAfter: 20,
    slidesOffsetBefore: 20,
});

const datesElement = document.querySelector(".select-dates");
const today = new Date();
const dates = [];

const lastDayOfMonth = new Date(
    today.getFullYear(),
    today.getMonth() + 1,
    0
).getDate();

for (let i = today.getDate(); i <= lastDayOfMonth; i++) {
    const date = new Date(today.getFullYear(), today.getMonth(), i);
    const month = date.toLocaleString("default", {
        month: "short",
    });

    const realDate = new Date(date.getTime() + 1000 * 60 * 60 * 24)
        .toISOString()
        .split("T")[0];

    dates.push(realDate);

    datesElement.innerHTML += `
        <div class="swiper-slide !w-fit py-[2px]">
            <label class="relative flex flex-col items-center justify-center w-fit rounded-3xl p-[14px_20px] gap-3 bg-white border border-white hover:border-[#91BF77] has-[:checked]:ring-2 has-[:checked]:ring-[#91BF77] transition-all duration-300">
                <img src="/assets/images/icons/calendar.svg" class="w-8 h-8" alt="icon">
                <p class="font-semibold text-nowrap">${date.getDate()} ${month}</p>
                <input type="radio" name="start_date" class="absolute top-1/2 left-1/2 -z-10 opacity-0" value="${realDate}" required>
            </label>
        </div>`;
}

const durationInput = document.getElementById("Duration");
const priceElement = document.getElementById("price");

function updatePrice() {
    const totalPrice = defaultPrice * 1;
    priceElement.innerHTML = `Rp ${totalPrice.toLocaleString("id-ID")}`;
}

function validateInput(value) {
    value = value.replace(/\D/g, "").slice(0, 3);
    if (parseInt(value, 10) === 0) {
        return "1";
    }
    return value;
}

durationInput.addEventListener("input", () => {
    let value = validateInput(durationInput.value);

    if (value === "") {
        durationInput.value = "";
        priceElement.innerHTML = "Rp 0";
        return;
    }

    durationInput.value = value;
    updatePrice();
});

durationInput.addEventListener("blur", () => {
    if (durationInput.value === "" || parseInt(durationInput.value, 10) === 0) {
        durationInput.value = "1";
        updatePrice();
    }
});

// Inisialisasi harga awal
updatePrice();

// Set end_time otomatis 24 jam setelah start_time
const startTimeInput = document.getElementById("start_time");
const endTimeInput = document.getElementById("end_time");

if (startTimeInput && endTimeInput) {
    startTimeInput.addEventListener("input", function () {
        if (this.value) {
            // Parse jam mulai
            const [hours, minutes] = this.value.split(":").map(Number);
            // Tambah 24 jam
            const endDate = new Date();
            endDate.setHours(hours + 24, minutes, 0, 0);
            // Format kembali ke HH:MM
            const endHours = String(endDate.getHours()).padStart(2, "0");
            const endMinutes = String(endDate.getMinutes()).padStart(2, "0");
            endTimeInput.value = `${endHours}:${endMinutes}`;
        } else {
            endTimeInput.value = "";
        }
    });
}
