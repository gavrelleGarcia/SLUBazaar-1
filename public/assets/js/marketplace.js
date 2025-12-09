// hard coded mock data
const mockItems = [
    {
        id: 1,
        title: "Seiko 5 SNK809 (Used)",
        price: 4500,
        seller: "JohnDoe",
        image: "https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&q=80&w=400",
        status: "Winning", // winning, outbid, sold, won, active
        endTime: "Dec 12, 05:00 PM"
    },
    {
        id: 2,
        title: "Keychron K2 Mechanical",
        price: 3200,
        seller: "JaneSmith",
        image: "https://images.unsplash.com/photo-1595225476474-87563907a212?auto=format&fit=crop&q=80&w=400",
        status: "Outbid",
        endTime: "Dec 10, 08:30 AM"
    },
    {
        id: 3,
        title: "Calculus Textbook (7th Ed)",
        price: 800,
        seller: "Me", // Logic: isOwner
        image: "https://images.unsplash.com/photo-1585338447937-7082f8fc763d?auto=format&fit=crop&q=80&w=400",
        status: "Sold",
        endTime: "Closed: Dec 05"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    },
    {
        id: 4,
        title: "Nike Air Max 90",
        price: 5100,
        seller: "ShoeLover",
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400",
        status: "Won",
        endTime: "Won on: Dec 01"
    }
];

document.addEventListener('DOMContentLoaded', () => {
    loadAuctions();
    setupTabs();
});

// --- 2. DATA FETCHING (Simulating DB Connection) ---
async function fetchAuctions() {
    // TODO: Later, replace this with: 
    // const response = await fetch('index.php?action=api_get_items');
    // return await response.json();
    
    return new Promise((resolve) => {
        setTimeout(() => resolve(mockItems), 300); // Simulate network delay
    });
}

// --- 3. RENDERING LOGIC ---
async function loadAuctions() {
    const container = document.getElementById('auction-container');
    container.innerHTML = '<p style="text-align:center;">Loading...</p>';

    const items = await fetchAuctions();
    container.innerHTML = ''; 

    items.forEach(item => {
        const cardHTML = createCardHTML(item);
        container.innerHTML += cardHTML;
    });
}

function createCardHTML(item) {
    

    let borderClass = '';
    let badgeHTML = '';
    let priceColor = '#333';
    let btnHTML = '';

    // Status Logic Switch
    switch(item.status) {
        case 'Winning':
            badgeHTML = `<span class="status-badge badge-winning">Winning</span>`;
            btnHTML = `<button class="btn-action btn-bid open-modal-btn" onclick="openModal('${item.title}', '${item.price}', '${item.seller}')">Bid Again</button>`;
            break;
        case 'Outbid':
            badgeHTML = `<span class="status-badge badge-outbid">Outbid</span>`;
            btnHTML = `<button class="btn-action btn-bid open-modal-btn" onclick="openModal('${item.title}', '${item.price}', '${item.seller}')">Bid Higher</button>`;
            break;
        case 'Sold':
            borderClass = 'border-blue';
            badgeHTML = `<span class="status-badge badge-sold">Sold</span>`;
            btnHTML = `<button class="btn-action btn-message"><i class="fa-regular fa-comment-dots"></i> Message Buyer</button>`;
            break;
        case 'Won':
            borderClass = 'border-green';
            badgeHTML = `<span class="status-badge badge-won">You Won!</span>`;
            priceColor = '#16a34a';
            btnHTML = `<button class="btn-action btn-message"><i class="fa-regular fa-comment-dots"></i> Message Seller</button>`;
            break;
        default:
            btnHTML = `<button class="btn-action btn-bid" onclick="openModal('${item.title}', '${item.price}', '${item.seller}')">Bid Now</button>`;
    }

    return `
        <div class="item-card ${borderClass}">
            <div class="card-img-wrapper">
                <img src="${item.image}" alt="${item.title}">
                ${badgeHTML}
            </div>
            <h4>${item.title}</h4>
            <div class="item-price" style="color: ${priceColor};">₱ ${item.price.toLocaleString()}</div>
            <div class="item-timer">${item.endTime}</div>
            <div style="margin-top: auto; width: 100%;">
                ${btnHTML}
            </div>
        </div>
    `;
}

// --- 4. UI INTERACTION (Tabs & Modal) ---
function setupTabs() {
    const tabs = document.querySelectorAll('.d-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            // Here you would normally filter the 'mockItems' array and re-render
        });
    });
}

// Modal Logic
const modal = document.getElementById('item-modal');
const modalTitle = document.getElementById('modal-title');
const modalPrice = document.getElementById('modal-price');
const modalNext = document.getElementById('modal-next');
const modalSeller = document.getElementById('modal-seller');

// Made global so HTML onclick can access it
window.openModal = function(title, price, seller) {
    modalTitle.innerText = title;
    modalPrice.innerText = parseInt(price).toLocaleString();
    modalNext.innerText = (parseInt(price) + 100).toLocaleString();
    modalSeller.innerText = seller;
    modal.classList.add('active');
}

window.closeModal = function() {
    modal.classList.remove('active');
}

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}