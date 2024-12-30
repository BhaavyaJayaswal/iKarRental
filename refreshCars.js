const filterForm = document.querySelector('#filter-form');
const carList = document.querySelector('#car-list');
const applyFiltersButton = document.querySelector('#apply-filters');

// Function to fetch and refresh car list
async function refreshCars() {
    // Collect filter values
    const passengers = document.querySelector('#filter-passengers').value;
    const from = document.querySelector('#filter-from').value;
    const until = document.querySelector('#filter-until').value;
    const transmission = document.querySelector('#filter-transmission').value;
    const priceMin = document.querySelector('#filter-price-min').value;
    const priceMax = document.querySelector('#filter-price-max').value;

    // Build query string
    const queryString = new URLSearchParams({
        ...(passengers && { passengers }),
        ...(from && { from }),
        ...(until && { until }),
        ...(transmission && { transmission }),
        ...(priceMin && { price_min: priceMin }),
        ...(priceMax && { price_max: priceMax })
    });

    // Fetch filtered results
    const response = await fetch('filterCars.php?' +queryString.toString());
    console.log('Request URL:', 'filterCars.php?' + queryString.toString());
    const cars = await response.json();


    // Clear the car list
    carList.innerHTML = '';
    if (cars.length === 0) 
        carList.innerHTML = '<p>No cars found with the selected filters.</p>';    
    // Populate the car list with filtered cars
    cars.forEach(car => {
        const carCard = document.createElement('div');
        carCard.classList.add('car-card');

        carCard.innerHTML = `
            <a href="details.php?id=${car.id}">
                <img src="${car.image}" alt="${car.brand} ${car.model}">
            </a>
            <div class="car-info">
                <h3>${car.brand} ${car.model}</h3>
                <p>${car.passengers} seats - ${car.transmission}</p>
                <p>${parseInt(car.daily_price_huf).toLocaleString('en-US')} Ft</p>
                <a href="details.php?id=${car.id}" class="btn">Book</a>
            </div>
        `;
        carList.appendChild(carCard);
    });
}

// Add event listener to filter button
applyFiltersButton.addEventListener('click', refreshCars);
