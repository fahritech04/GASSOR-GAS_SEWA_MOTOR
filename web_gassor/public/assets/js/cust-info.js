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
            <label class="relative flex flex-col items-center justify-center w-fit rounded-3xl p-[14px_20px] gap-3 bg-white border border-white hover:border-[#E6A43B] has-[:checked]:ring-2 has-[:checked]:ring-[#E6A43B] transition-all duration-300">
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

// harga awal
updatePrice();
const startTimeInput = document.getElementById("start_time");
const endTimeInput = document.getElementById("end_time");
const endTimeHidden = document.getElementById("end_time_hidden");

if (startTimeInput && endTimeInput) {
    startTimeInput.addEventListener("input", function () {
        if (this.value) {
            let [hours, minutes] = this.value.split(":").map(Number);

            const startRentHour =
                typeof window.startRentHour !== "undefined"
                    ? window.startRentHour
                    : startRentHour;
            const endRentHour =
                typeof window.endRentHour !== "undefined"
                    ? window.endRentHour
                    : endRentHour;

            const [minHour, minMinute] = startRentHour.split(":").map(Number);
            const [maxHour, maxMinute] = endRentHour.split(":").map(Number);

            if (hours < minHour || (hours === minHour && minutes < minMinute)) {
                hours = minHour;
                minutes = minMinute;
                this.value = `${String(hours).padStart(2, "0")}:${String(
                    minutes
                ).padStart(2, "0")}`;
            } else if (
                hours > maxHour ||
                (hours === maxHour && minutes > maxMinute)
            ) {
                hours = maxHour;
                minutes = maxMinute;
                this.value = `${String(hours).padStart(2, "0")}:${String(
                    minutes
                ).padStart(2, "0")}`;
            }

            // Tambah 24 jam untuk end_time
            const endDate = new Date();
            endDate.setHours(hours + 24, minutes, 0, 0);
            const endHours = String(endDate.getHours()).padStart(2, "0");
            const endMinutes = String(endDate.getMinutes()).padStart(2, "0");
            const endTimeValue = `${endHours}:${endMinutes}`;

            // Update display select dan hidden input
            endTimeInput.innerHTML = `<option value="${endTimeValue}" selected>${endTimeValue} (+24 jam)</option>`;
            if (endTimeHidden) {
                endTimeHidden.value = endTimeValue;
            }
        } else {
            endTimeInput.innerHTML =
                '<option value="">Otomatis +24 jam</option>';
            if (endTimeHidden) {
                endTimeHidden.value = "";
            }
        }
    });
}
