// ============================================================
// script.js (Week 5)
// Kartu barang utama sekarang dibuat oleh PHP (index.php).
// JavaScript hanya mengurus: popup detail, favorit, akun, dan posting barang.
// ============================================================

const namaStatus    = { tersedia: 'Tersedia', nego: 'Nego', terjual: 'Terjual' };
const namaKategori  = { buku: 'Buku', elektronik: 'Elektronik', perabot: 'Perabot Kos' };
const emojiKategori = { buku: '📚', elektronik: '🔌', perabot: '🪑' };
const formatRupiah  = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n);

function baca(kunci, cadangan) {
  try { return JSON.parse(localStorage.getItem(kunci)) ?? cadangan; }
  catch { return cadangan; }
}
function simpanKe(kunci, nilai) {
  try { localStorage.setItem(kunci, JSON.stringify(nilai)); }
  catch { alert('Penyimpanan browser penuh, data tidak bisa disimpan permanen.'); }
}

// ===== DATA DARI PHP =====
// PHP menaruh seluruh barang di <script id="barang-data"> (format JSON).
const dataServer = JSON.parse(document.getElementById('barang-data').textContent);

// Filter yang sedang aktif, juga ditentukan oleh PHP lewat atribut <body>
const kategoriAktif = document.body.dataset.kategori || 'semua';
const kataKunci     = (document.body.dataset.q || '').toLowerCase();
const adaGalat      = document.body.dataset.galat === '1';

// ===== DATA DARI BROWSER (localStorage) =====
let postingan  = baca('mk_posts', []);      // barang yang diposting user
let wishlist   = baca('mk_wishlist', []);   // id barang favorit
let userLogin  = baca('mk_user', null);     // nama user yang login
let daftarAkun = baca('mk_accounts', []);   // akun yang sudah terdaftar

const semuaBarang = () => [...dataServer, ...postingan];

// ===== ELEMEN =====
const productList  = document.getElementById('product-list');
const hasilInfo    = document.getElementById('hasil-info');
const emptyMessage = document.getElementById('empty-message');

const navKategori = document.getElementById('nav-kategori');
const navPost     = document.getElementById('nav-post');
const navDaftar   = document.getElementById('nav-daftar');
const liDaftar    = document.getElementById('li-daftar');
const navLogin    = document.getElementById('nav-login');

const dialogDetail = document.getElementById('dialog-detail');
const dialogDaftar = document.getElementById('dialog-daftar');
const dialogLogin  = document.getElementById('dialog-login');
const dialogPost   = document.getElementById('dialog-post');
const formDaftar   = document.getElementById('form-daftar');
const formLogin    = document.getElementById('form-login');
const formPost     = document.getElementById('form-post');

// ===== KOTAK EMOJI PENGGANTI GAMBAR =====
function buatPlaceholder(kategori) {
  const kosong = document.createElement('div');
  kosong.className = 'img-placeholder';
  kosong.textContent = emojiKategori[kategori] || '📦';
  return kosong;
}

// Kalau file foto tidak ditemukan, ganti dengan emoji supaya kartu tetap rapi
function pasangFallbackGambar(induk) {
  induk.querySelectorAll('.product-card img').forEach((img) => {
    const ganti = () => img.replaceWith(buatPlaceholder(img.closest('.product-card').dataset.kategori));
    if (img.complete && img.naturalWidth === 0) ganti();
    else img.addEventListener('error', ganti, { once: true });
  });
}

// ===== KARTU UNTUK BARANG YANG DIPOSTING USER =====
// Strukturnya sama persis dengan kartu buatan PHP (renderKartu di functions.php)
function buatKartu(item) {
  const card = document.createElement('article');
  card.className = 'product-card' + (item.status === 'terjual' ? ' product-card--terjual' : '');
  card.id = 'barang-' + item.id;
  card.dataset.id = item.id;
  card.dataset.kategori = item.kategori;

  if (item.foto) {
    const img = document.createElement('img');
    img.src = item.foto;
    img.alt = item.nama;
    img.loading = 'lazy';
    card.appendChild(img);
  } else {
    card.appendChild(buatPlaceholder(item.kategori));
  }

  const wish = document.createElement('button');
  wish.type = 'button';
  wish.className = 'btn-wish';
  wish.textContent = '♡';
  wish.setAttribute('aria-label', 'Tambah ke favorit');
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

// Aturan filter yang sama dengan filterBarang() di PHP
function cocokFilter(item) {
  const teks = `${item.nama} ${item.deskripsi} ${namaKategori[item.kategori]}`.toLowerCase();
  const cocokKata = teks.includes(kataKunci);
  const cocokKategori = kategoriAktif === 'semua' || kategoriAktif === 'favorit' || item.kategori === kategoriAktif;
  return cocokKata && cocokKategori;
}

// ===== FAVORIT =====
function setTombolWish(card) {
  const btn = card.querySelector('.btn-wish');
  const aktif = wishlist.includes(Number(card.dataset.id));
  btn.textContent = aktif ? '♥' : '♡';
  btn.classList.toggle('active', aktif);
  btn.setAttribute('aria-label', aktif ? 'Hapus dari favorit' : 'Tambah ke favorit');
}

function sinkronkanFavorit() {
  productList.querySelectorAll('.product-card').forEach((card) => {
    setTombolWish(card);
    // Di halaman ?kategori=favorit, tampilkan hanya yang ada di wishlist
    if (kategoriAktif === 'favorit') card.hidden = !wishlist.includes(Number(card.dataset.id));
  });
}

// ===== INFO JUMLAH & PESAN KOSONG =====
function perbaruiInfo() {
  const jumlah = productList.querySelectorAll('.product-card:not([hidden])').length;
  hasilInfo.textContent = `Menampilkan ${jumlah} barang`;
  emptyMessage.hidden = jumlah > 0 || adaGalat;
  emptyMessage.textContent = kategoriAktif === 'favorit' && !kataKunci
    ? 'Belum ada barang favorit. Klik ♡ pada barang untuk menyimpannya.'
    : 'Barang tidak ditemukan. Coba kata kunci atau kategori lain.';
}

// ===== NAVIGASI =====
navKategori.addEventListener('click', (e) => {
  e.preventDefault();
  document.getElementById('kategori').scrollIntoView({ behavior: 'smooth', block: 'center' });
});

// ===== KLIK DI DAFTAR BARANG (Detail & Favorit) =====
// Satu listener di induk, jadi kartu buatan PHP maupun buatan JS sama-sama berfungsi
productList.addEventListener('click', (e) => {
  const card = e.target.closest('.product-card');
  if (!card) return;
  const id = Number(card.dataset.id);

  if (e.target.closest('.btn-wish')) {
    wishlist = wishlist.includes(id) ? wishlist.filter((x) => x !== id) : [...wishlist, id];
    simpanKe('mk_wishlist', wishlist);
    setTombolWish(card);
    if (kategoriAktif === 'favorit') card.hidden = !wishlist.includes(id);
    perbaruiInfo();
    return;
  }

  if (e.target.closest('.btn-detail')) {
    bukaDetail(semuaBarang().find((b) => b.id === id));
  }
});

function bukaDetail(item) {
  if (!item) return;

  const gambarBox = document.getElementById('detail-gambar');
  gambarBox.replaceChildren();
  if (item.foto) {
    const img = document.createElement('img');
    img.src = item.foto;
    img.alt = item.nama;
    img.addEventListener('error', () => img.replaceWith(buatPlaceholder(item.kategori)), { once: true });
    gambarBox.appendChild(img);
  } else {
    gambarBox.appendChild(buatPlaceholder(item.kategori));
  }

  document.getElementById('detail-nama').textContent = item.nama;
  document.getElementById('detail-harga').textContent = formatRupiah(item.harga);
  document.getElementById('detail-merk').textContent = item.merk || '-';
  document.getElementById('detail-ukuran').textContent = item.ukuran || '-';
  document.getElementById('detail-kondisi').textContent = item.kondisi || '-';
  document.getElementById('detail-kategori').textContent = 'Kategori: ' + namaKategori[item.kategori];
  document.getElementById('detail-deskripsi').textContent = item.deskripsi;

  const statusEl = document.getElementById('detail-status');
  statusEl.textContent = namaStatus[item.status];
  statusEl.className = 'status status--' + item.status;

  const pesan = `Halo, saya tertarik dengan "${item.nama}" di Preloved.U. Apakah masih tersedia?`;
  document.getElementById('detail-wa').href = `https://wa.me/${item.wa}?text=${encodeURIComponent(pesan)}`;

  dialogDetail.showModal();
}

// ===== AKUN: DAFTAR & LOGIN =====
function updateTombolLogin() {
  navLogin.textContent = userLogin ? `Logout (${userLogin})` : 'Login';
  liDaftar.hidden = !!userLogin;   // tombol Daftar hilang kalau sudah login
}

function bukaDaftar() {
  formDaftar.reset();
  document.getElementById('daftar-error').textContent = '';
  dialogDaftar.showModal();
}

function bukaLogin(pesan, emailIsi = '') {
  formLogin.reset();
  document.getElementById('login-info').textContent = pesan;
  document.getElementById('login-error').textContent = '';
  document.getElementById('login-email').value = emailIsi;
  dialogLogin.showModal();
}

navDaftar.addEventListener('click', (e) => {
  e.preventDefault();
  bukaDaftar();
});

navLogin.addEventListener('click', (e) => {
  e.preventDefault();
  if (userLogin) {
    userLogin = null;
    simpanKe('mk_user', null);
    updateTombolLogin();
    alert('Kamu sudah logout.');
  } else {
    bukaLogin('Masuk untuk memposting barang.');
  }
});

// pindah antar dialog
document.getElementById('ke-daftar').addEventListener('click', () => { dialogLogin.close(); bukaDaftar(); });
document.getElementById('ke-login').addEventListener('click', () => { dialogDaftar.close(); bukaLogin('Masuk untuk memposting barang.'); });

formDaftar.addEventListener('submit', (e) => {
  e.preventDefault();
  const tampilError = (pesan) => { document.getElementById('daftar-error').textContent = pesan; };

  const nama  = document.getElementById('daftar-nama').value.trim();
  const email = document.getElementById('daftar-email').value.trim().toLowerCase();
  const pass  = document.getElementById('daftar-password').value;

  if (daftarAkun.some((a) => a.email === email)) return tampilError('Email ini sudah terdaftar. Silakan login.');
  if (pass.length < 6) return tampilError('Password minimal 6 karakter.');

  daftarAkun.push({ nama, email, password: pass });
  simpanKe('mk_accounts', daftarAkun);

  dialogDaftar.close();
  bukaLogin('Akun berhasil dibuat! Silakan login.', email);
});

formLogin.addEventListener('submit', (e) => {
  e.preventDefault();
  const tampilError = (pesan) => { document.getElementById('login-error').textContent = pesan; };

  const email = document.getElementById('login-email').value.trim().toLowerCase();
  const pass  = document.getElementById('login-password').value;
  const akun  = daftarAkun.find((a) => a.email === email);

  if (!akun) return tampilError('Akun belum terdaftar. Silakan daftar dulu.');
  if (akun.password !== pass) return tampilError('Password salah.');

  userLogin = akun.nama;
  simpanKe('mk_user', userLogin);
  updateTombolLogin();
  dialogLogin.close();
});

// ===== POST BARANG =====
navPost.addEventListener('click', (e) => {
  e.preventDefault();
  if (!userLogin) {
    bukaLogin('Kamu harus login dulu untuk memposting barang. Belum punya akun? Daftar dulu.');
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
    merk: document.getElementById('post-merk').value.trim(),
    ukuran: document.getElementById('post-ukuran').value.trim(),
    kondisi: document.getElementById('post-kondisi').value,
    harga: Number(document.getElementById('post-harga').value),
    kategori: document.getElementById('post-kategori').value,
    status: document.getElementById('post-status').value,
    wa: document.getElementById('post-wa').value,
    deskripsi: document.getElementById('post-deskripsi').value.trim(),
    foto: fotoData,
  };

  postingan.push(item);
  simpanKe('mk_posts', postingan);

  formPost.reset();
  dialogPost.close();

  // Kalau sedang memakai filter (?q= atau ?kategori=), kembali ke halaman penuh
  // supaya barang baru pasti terlihat. Tanpa filter, cukup ditambahkan ke daftar.
  if (window.location.search !== '') {
    window.location.href = 'index.php#barang-' + item.id;
    return;
  }

  const kartu = buatKartu(item);
  productList.appendChild(kartu);
  pasangFallbackGambar(kartu);
  setTombolWish(kartu);
  perbaruiInfo();
  kartu.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ===== TUTUP DIALOG =====
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

// 1) Tambahkan barang buatan user (dari localStorage) setelah kartu buatan PHP
postingan.filter(cocokFilter).forEach((item) => productList.appendChild(buatKartu(item)));

// 2) Rapikan: foto rusak, tombol favorit, mode favorit, dan jumlah barang
pasangFallbackGambar(productList);
sinkronkanFavorit();
perbaruiInfo();

// 3) Kalau datang dari posting barang, geser ke kartu barunya
if (window.location.hash.startsWith('#barang-')) {
  document.getElementById(window.location.hash.slice(1))?.scrollIntoView({ block: 'center' });
}