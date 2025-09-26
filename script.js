// ===== DOM ELEMENTS =====
const formPengaduan = document.getElementById('form-pengaduan');
const formStatus = document.getElementById('form-status');
const darkModeToggle = document.getElementById('dark-mode-toggle');
const body = document.body;

// ===== DARK MODE FUNCTIONALITY =====
if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    darkModeToggle.textContent = '☀️ Mode Terang';
} else {
    body.classList.remove('dark-mode');
    darkModeToggle.textContent = '🌙 Mode Gelap';
}

// Dark mode toggle event
darkModeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    
    if (body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
        darkModeToggle.textContent = '☀️ Mode Terang';
    } else {
        localStorage.setItem('darkMode', 'disabled');
        darkModeToggle.textContent = '🌙 Mode Gelap';
    }
});

// ===== FETCH API IMPLEMENTATION =====
// API untuk mendapatkan quote motivasi tentang pendidikan
async function fetchEducationQuote() {
    try {
        const response = await fetch('https://api.quotable.io/random?tags=education|knowledge|learning');
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        displayQuoteInfo(data);
    } catch (error) {
        console.error('Error fetching quote data:', error);
        // Fallback quote tentang pendidikan
        displayQuoteInfo({
            content: "Pendidikan adalah senjata paling ampuh yang dapat digunakan untuk mengubah dunia.",
            author: "Nelson Mandela",
            tags: ["education"]
        });
    }
}

// Display quote information
function displayQuoteInfo(quoteData) {
    const quoteElement = document.getElementById('quote-info');
    if (quoteElement) {
        quoteElement.innerHTML = `
            <div class="api-card">
                <h3>💡Kata-Kata hari ini </h3>
                <p class="quote-content">"${quoteData.content}"</p>
                <p class="quote-author">- ${quoteData.author}</p>
            </div>
        `;
    }
}

// ===== BERITA PENDIDIKAN =====
async function fetchEducationNews() {
    try {
        const response = await fetch('https://content.guardianapis.com/search?q=university%20education&show-fields=thumbnail,headline&api-key=test');
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        displayNewsInfo(data);
    } catch (error) {
        console.error('Error fetching news data:', error);
        // Fallback berita lokal tentang pendidikan
        displayNewsInfo({
            response: {
                results: [
                    {
                        webTitle: "Universitas Mulawarman Terus Tingkatkan Layanan Akademik",
                        fields: {
                            headline: "Inovasi Layanan Mahasiswa di Era Digital",
                            thumbnail: ""
                        },
                        webUrl: "#"
                    }
                ]
            }
        });
    }
}

// Display news information
function displayNewsInfo(newsData) {
    const newsElement = document.getElementById('news-info');
    if (newsElement && newsData.response && newsData.response.results) {
        const articles = newsData.response.results.slice(0, 2);
        let newsHTML = '<div class="news-card"><h3>📰 Berita Pendidikan Terkini</h3>';
        
        articles.forEach(article => {
            newsHTML += `
                <div class="news-item">
                    <h4>${article.webTitle}</h4>
                    <p>${article.fields?.headline || 'Berita terbaru tentang dunia pendidikan'}</p>
                    <a href="${article.webUrl}" target="_blank" class="news-link">Baca selengkapnya →</a>
                </div>
            `;
        });
        
        newsHTML += '</div>';
        newsElement.innerHTML = newsHTML;
    }
}

// ===== FORM HANDLING =====
if (formPengaduan) {
    formPengaduan.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(formPengaduan);
        const pengaduanData = {
            nama: formData.get('nama'),
            role: formData.get('role'),
            kategori: formData.get('kategori'),
            judul: formData.get('judul'),
            deskripsi: formData.get('deskripsi'),
            lampiran: formData.get('lampiran') ? formData.get('lampiran').name : 'Tidak ada',
            tanggal: new Date().toLocaleString('id-ID'),
            id: generateID()
        };
        
        savePengaduan(pengaduanData);
        showNotification('Pengaduan berhasil dikirim! ID Pengaduan: ' + pengaduanData.id, 'success');
        formPengaduan.reset();
    });
}

if (formStatus) {
    formStatus.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const kodePengaduan = document.getElementById('kode-pengaduan').value.trim();
        let statusResult = document.getElementById('status-result');
        
        if (!statusResult) {
            statusResult = document.createElement('div');
            statusResult.id = 'status-result';
            statusResult.className = 'status-result';
            formStatus.appendChild(statusResult);
        }
        
        const pengaduan = getPengaduanByID(kodePengaduan);
        
        if (pengaduan) {
            displayStatusResult(pengaduan);
        } else {
            showNotification('Pengaduan dengan ID ' + kodePengaduan + ' tidak ditemukan.', 'error');
        }
    });
}

// ===== HELPER FUNCTIONS =====
function generateID() {
    return 'UNMUL-' + Date.now().toString().substr(-6);
}

function savePengaduan(pengaduanData) {
    let pengaduanList = JSON.parse(localStorage.getItem('pengaduanList')) || [];
    pengaduanList.push(pengaduanData);
    localStorage.setItem('pengaduanList', JSON.stringify(pengaduanList));
}

function getPengaduanByID(id) {
    const pengaduanList = JSON.parse(localStorage.getItem('pengaduanList')) || [];
    return pengaduanList.find(pengaduan => pengaduan.id === id);
}

function displayStatusResult(pengaduan) {
    const statusResult = document.getElementById('status-result');
    
    const submitTime = new Date(pengaduan.tanggal);
    const now = new Date();
    const hoursDiff = (now - submitTime) / (1000 * 60 * 60);
    
    let status = 'Dalam Antrian';
    let statusClass = 'status-pending';
    
    if (hoursDiff > 24) {
        status = 'Sedang Diproses';
        statusClass = 'status-processing';
    }
    if (hoursDiff > 72) {
        status = 'Selesai';
        statusClass = 'status-completed';
    }
    
    statusResult.innerHTML = `
        <div class="status-card ${statusClass}">
            <h3>Status Pengaduan: ${pengaduan.id}</h3>
            <div class="status-details">
                <p><strong>Judul:</strong> ${pengaduan.judul}</p>
                <p><strong>Kategori:</strong> ${pengaduan.kategori}</p>
                <p><strong>Tanggal:</strong> ${pengaduan.tanggal}</p>
                <p><strong>Status:</strong> <span class="status-badge">${status}</span></p>
            </div>
        </div>
    `;
}

function showNotification(message, type) {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) existingNotification.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', () => {
    fetchEducationQuote();
    fetchEducationNews(); // Hanya 2 API yang aktif
});

// ===== SMOOTH SCROLLING =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});