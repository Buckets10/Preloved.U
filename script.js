const DATA_DUMMY = [
  { id: 1,  nama: 'Buku Pemrograman Web',            harga: 50000,  kategori: 'buku',       status: 'tersedia', wa: '628123456789', deskripsi: 'Buku bekas kondisi baik, tanpa coretan.', foto: 'img/buku 1.jpg' },
  { id: 2,  nama: 'Kipas Angin Kecil',               harga: 75000,  kategori: 'elektronik', status: 'nego',     wa: '628123456789', deskripsi: 'Kipas angin mini, masih berfungsi normal.', foto: 'img/PASFOT.jpeg' },
  { id: 3,  nama: 'Mouse Wireless',                  harga: 35000,  kategori: 'elektronik', status: 'tersedia', wa: '628123456789', deskripsi: 'Mouse wireless, baterai baru diganti.', foto: 'img/mouse.jpg' },
  { id: 4,  nama: 'Rak Buku Kayu',                   harga: 120000, kategori: 'perabot',    status: 'tersedia', wa: '628123456789', deskripsi: 'Rak 4 tingkat, cocok untuk kamar kos.', foto: 'img/rakbuku.jpg' },
  { id: 5,  nama: 'Meja Belajar Lipat',              harga: 90000,  kategori: 'perabot',    status: 'tersedia', wa: '628123456789', deskripsi: 'Meja lipat ringan, mudah dibawa pindahan.', foto: 'img/Mejalipat.jpg' },
];

const namaStatus   = { tersedia: 'Tersedia', nego: 'Nego', terjual: 'Terjual' };
const namaKategori = { buku: 'Buku', elektronik: 'Elektronik', perabot: 'Perabot Kos' };
const emojiKategori = { buku: '📚', elektronik: '🔌', perabot: '🪑' };
const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n);

function baca(kunci, cadangan) {
  try { return JSON.parse(localStorage.getItem(kunci)) ?? cadangan; }
  catch { return cadangan; }
}
function simpanKe(kunci, nilai) {
  try { localStorage.setItem(kunci, JSON.stringify(nilai)); }
  catch { alert('Penyimpanan browser penuh, data tidak bisa disimpan permanen.'); }
}

let postingan = baca('mk_posts', []);      // barang yang diposting user
let wishlist  = baca('mk_wishlist', []);   // daftar id barang favorit
let userLogin = baca('mk_user', null);     // nama user yang login
let kategoriAktif = 'semua';
let kataKunci = '';

const semuaBarang = () => [...DATA_DUMMY, ...postingan];

const searchInput  = document.getElementById('search');
const btnCari      = document.getElementById('btn-cari');
const productList  = document.getElementById('product-list');
const hasilInfo    = document.getElementById('hasil-info');
const emptyMessage = document.getElementById('empty-message');
const chips        = document.querySelectorAll('.chip');

const navBeranda  = document.getElementById('nav-beranda');
const navKategori = document.getElementById('nav-kategori');
const navPost     = document.getElementById('nav-post');
const navLogin    = document.getElementById('nav-login');

const dialogDetail = document.getElementById('dialog-detail');
const dialogLogin  = document.getElementById('dialog-login');
const dialogPost   = document.getElementById('dialog-post');
const formLogin    = document.getElementById('form-login');
const formPost     = document.getElementById('form-post');

function buatKartu(item) {
  const card = document.createElement('article');
  card.className = 'product-card';
  card.dataset.id = item.id;

  if (item.foto) {
    const img = document.createElement('img');
    img.src = item.foto;
    img.alt = item.nama;
    img.loading = 'lazy';
    card.appendChild(img);
  } else {
    const kosong = document.createElement('div');
    kosong.className = 'img-placeholder';
    kosong.textContent = emojiKategori[item.kategori];
    card.appendChild(kosong);
  }

  const wish = document.createElement('button');
  wish.type = 'button';
  wish.className = 'btn-wish';
  const difavoritkan = wishlist.includes(item.id);
  wish.textContent = difavoritkan ? '♥' : '♡';
  wish.classList.toggle('active', difavoritkan);
  wish.setAttribute('aria-label', difavoritkan ? 'Hapus dari favorit' : 'Tambah ke favorit');
  card.appendChild(wish);

  const judul = document.createElement('h2');
  judul.textContent = item.nama;

  const harga = document.createElement('p');
  harga.className = 'price';
  harga.textContent = formatRupiah(item.harga);

  const status = document.createElement('span');
  status.className = 'status status--' + item.status;
  status.textContent = namaStatus[item.status];

  const tombol = document.createElement('button');
  tombol.type = 'button';
  tombol.className = 'btn-detail';
  tombol.textContent = 'Lihat Detail';

  card.append(judul, harga, status, tombol);
  return card;
}

function render() {
  productList.querySelectorAll('.product-card').forEach((c) => c.remove());

  const hasil = semuaBarang().filter((item) => {
    const teks = `${item.nama} ${item.deskripsi} ${namaKategori[item.kategori]}`.toLowerCase();
    const cocokKata = teks.includes(kataKunci);
    const cocokKategori =
      kategoriAktif === 'semua' ? true :
      kategoriAktif === 'favorit' ? wishlist.includes(item.id) :
      item.kategori === kategoriAktif;
    return cocokKata && cocokKategori;
  });

  hasil.forEach((item) => productList.appendChild(buatKartu(item)));

  hasilInfo.textContent = `Menampilkan ${hasil.length} barang`;
  emptyMessage.hidden = hasil.length > 0;
  emptyMessage.textContent = kategoriAktif === 'favorit' && !kataKunci
    ? 'Belum ada barang favorit. Klik ♡ pada barang untuk menyimpannya.'
    : 'Barang tidak ditemukan. Coba kata kunci atau kategori lain.';
}

function jalankanCari() {
  kataKunci = searchInput.value.toLowerCase().trim();
  render();
}
btnCari.addEventListener('click', jalankanCari);
searchInput.addEventListener('input', jalankanCari);   // hasil langsung berubah saat mengetik

chips.forEach((chip) => {
  chip.addEventListener('click', () => {
    kategoriAktif = chip.dataset.kategori;
    chips.forEach((c) => c.classList.toggle('active', c === chip));
    render();
  });
});

navBeranda.addEventListener('click', (e) => {
  e.preventDefault();
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

navKategori.addEventListener('click', (e) => {
  e.preventDefault();
  document.getElementById('kategori').scrollIntoView({ behavior: 'smooth', block: 'center' });
});

productList.addEventListener('click', (e) => {
  const card = e.target.closest('.product-card');
  if (!card) return;
  const id = Number(card.dataset.id);

  if (e.target.closest('.btn-wish')) {
    wishlist = wishlist.includes(id) ? wishlist.filter((x) => x !== id) : [...wishlist, id];
    simpanKe('mk_wishlist', wishlist);
    render();
    return;
  }

  if (e.target.closest('.btn-detail')) {
    bukaDetail(semuaBarang().find((b) => b.id === id));
  }
});

function bukaDetail(item) {
  const gambarBox = document.getElementById('detail-gambar');
  gambarBox.replaceChildren();
  if (item.foto) {
    const img = document.createElement('img');
    img.src = item.foto;
    img.alt = item.nama;
    gambarBox.appendChild(img);
  } else {
    const kosong = document.createElement('div');
    kosong.className = 'img-placeholder';
    kosong.textContent = emojiKategori[item.kategori];
    gambarBox.appendChild(kosong);
  }

  document.getElementById('detail-nama').textContent = item.nama;
  document.getElementById('detail-harga').textContent = formatRupiah(item.harga);
  document.getElementById('detail-kategori').textContent = 'Kategori: ' + namaKategori[item.kategori];
  document.getElementById('detail-deskripsi').textContent = item.deskripsi;

  const statusEl = document.getElementById('detail-status');
  statusEl.textContent = namaStatus[item.status];
  statusEl.className = 'status status--' + item.status;

  const pesan = `Halo, saya tertarik dengan "${item.nama}" di Marketplace Kampus. Apakah masih tersedia?`;
  document.getElementById('detail-wa').href = `https://wa.me/${item.wa}?text=${encodeURIComponent(pesan)}`;

  dialogDetail.showModal();
}

function updateTombolLogin() {
  navLogin.textContent = userLogin ? `Logout (${userLogin})` : 'Login';
}

navLogin.addEventListener('click', (e) => {
  e.preventDefault();
  if (userLogin) {
    userLogin = null;
    simpanKe('mk_user', null);
    updateTombolLogin();
    alert('Kamu sudah logout.');
  } else {
    document.getElementById('login-info').textContent = 'Masuk untuk memposting barang.';
    dialogLogin.showModal();
  }
});

formLogin.addEventListener('submit', (e) => {
  e.preventDefault();
  userLogin = document.getElementById('login-nama').value.trim();
  simpanKe('mk_user', userLogin);
  updateTombolLogin();
  formLogin.reset();
  dialogLogin.close();
});

// ===== POST BARANG =====
navPost.addEventListener('click', (e) => {
  e.preventDefault();
  if (!userLogin) {
    document.getElementById('login-info').textContent = 'Kamu harus login dulu untuk memposting barang.';
    dialogLogin.showModal();
    return;
  }
  dialogPost.showModal();
});

formPost.addEventListener('submit', (e) => {
  e.preventDefault();
  const foto = document.getElementById('post-foto').files[0];

  if (foto) {
    const reader = new FileReader();
    reader.onload = () => {
      // foto disimpan sebagai teks (dataURL); kalau terlalu besar, dilewati agar penyimpanan tidak penuh
      const terlaluBesar = reader.result.length > 700000;
      if (terlaluBesar) alert('Foto terlalu besar, barang diposting tanpa foto. Pakai foto di bawah ±500 KB.');
      simpanBarang(terlaluBesar ? null : reader.result);
    };
    reader.readAsDataURL(foto);
  } else {
    simpanBarang(null);
  }
});

function simpanBarang(fotoData) {
  const item = {
    id: Date.now(),
    nama: document.getElementById('post-nama').value.trim(),
    harga: Number(document.getElementById('post-harga').value),
    kategori: document.getElementById('post-kategori').value,
    status: document.getElementById('post-status').value,
    wa: document.getElementById('post-wa').value,
    deskripsi: document.getElementById('post-deskripsi').value.trim(),
    foto: fotoData,
  };

  postingan.push(item);
  simpanKe('mk_posts', postingan);

  kategoriAktif = 'semua';
  kataKunci = '';
  searchInput.value = '';
  chips.forEach((c) => c.classList.toggle('active', c.dataset.kategori === 'semua'));

  formPost.reset();
  dialogPost.close();
  render();
  productList.querySelector(`[data-id="${item.id}"]`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

document.querySelectorAll('dialog').forEach((dlg) => {
  dlg.querySelectorAll('[data-close]').forEach((btn) =>
    btn.addEventListener('click', () => dlg.close())
  );
  dlg.addEventListener('click', (e) => {
    if (e.target === dlg) dlg.close();   // klik area gelap di luar dialog
  });
});

// ===== MULAI =====
updateTombolLogin();
render();