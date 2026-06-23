// assets/js/app.js
// Mengelola cart di sisi klien (disimpan di localStorage agar tidak hilang saat reload)
$(function(){
  const STORAGE_KEY = 'cafe_kasir_cart_v1';

  function loadCart() {
    const raw = localStorage.getItem(STORAGE_KEY);
    return raw ? JSON.parse(raw) : [];
  }
  function saveCart(cart) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
  }
  function renderCart() {
    const cart = loadCart();
    if (cart.length === 0) {
      $('#cart-table').hide();
      $('#cart-empty-message').show();
      return;
    }
    $('#cart-empty-message').hide();
    $('#cart-table').show();
    let rows = '';
    let total = 0;
    cart.forEach((item, idx) => {
      const subtotal = item.jumlah * item.harga;
      total += subtotal;
      rows += `<tr data-idx="${idx}">
        <td>${escapeHtml(item.nama)}</td>
        <td><input class="form-control form-control-sm qty-input" type="number" min="1" value="${item.jumlah}" style="width:80px;"></td>
        <td>Rp ${numberFormat(subtotal)}</td>
        <td><button class="btn btn-sm btn-danger btn-delete">Hapus</button></td>
      </tr>`;
    });
    $('#cart-body').html(rows);
    $('#cart-total').text('Rp ' + numberFormat(total));
  }

  function numberFormat(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
  function escapeHtml(text) {
    return String(text).replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; });
  }

  // Add button
  $(document).on('click', '.btn-add', function(){
    const id = $(this).data('id');
    const name = $(this).data('name');
    const price = parseInt($(this).data('price'), 10);
    let cart = loadCart();
    const idx = cart.findIndex(i => i.id_menu == id);
    if (idx >= 0) {
      cart[idx].jumlah += 1;
    } else {
      cart.push({id_menu: id, nama: name, harga: price, jumlah: 1});
    }
    saveCart(cart);
    renderCart();
    Swal.fire({ icon: 'success', title: 'Ditambahkan', text: name + ' ditambahkan ke keranjang', timer: 900, showConfirmButton:false });
  });

  // Update qty change
  $(document).on('change', '.qty-input', function(){
    const val = parseInt($(this).val(),10) || 1;
    const idx = $(this).closest('tr').data('idx');
    let cart = loadCart();
    if (cart[idx]) {
      cart[idx].jumlah = val;
      saveCart(cart);
      renderCart();
    }
  });

  // Delete item
  $(document).on('click', '.btn-delete', function(){
    const idx = $(this).closest('tr').data('idx');
    let cart = loadCart();
    if (cart[idx]) {
      cart.splice(idx,1);
      saveCart(cart);
      renderCart();
    }
  });

  // Save transaction
  $('#btn-save').on('click', function(){
    const cart = loadCart();
    if (cart.length === 0) {
      Swal.fire({ icon: 'warning', title: 'Keranjang kosong' });
      return;
    }
    const nama = $('#customer-name').val().trim();
    if (nama === '') {
      Swal.fire({ icon: 'warning', title: 'Masukkan nama pelanggan' });
      return;
    }
    // hit ke server
    $.ajax({
      url: '../transaksi/simpan.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ customer: nama, items: cart }),
      success: function(res){
        try { res = JSON.parse(res); } catch(e){}
        if (res && res.success) {
          Swal.fire({ icon: 'success', title: 'Transaksi tersimpan', text: 'ID: ' + res.id }).then(() => {
            // clear
            localStorage.removeItem(STORAGE_KEY);
            renderCart();
            $('#customer-name').val('');
            // buka cetak struk
            window.open('../transaksi/print.php?id=' + res.id, '_blank');
          });
        } else {
          Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.error) ? res.error : 'Terjadi kesalahan' });
        }
      },
      error: function(){
        Swal.fire({ icon: 'error', title: 'Gagal koneksi' });
      }
    });
  });

  // Inisialisasi
  renderCart();
});
