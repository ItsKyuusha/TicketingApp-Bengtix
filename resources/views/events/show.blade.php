<x-layouts.app>
  <section class="max-w-7xl mx-auto py-12 px-6">

    {{-- ===================== BREADCRUMB ===================== --}}
    <nav class="mb-6">
      <div class="breadcrumbs">
        <ul>
          <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
          <li><a href="#" class="link link-neutral">Event</a></li>
          <li>{{ $event->judul }}</li>
        </ul>
      </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      {{-- ===================== LEFT ===================== --}}
      <div class="lg:col-span-2">
        <div class="card bg-base-100 shadow">
          <figure>
            <img
              src="{{ $event->gambar
                  ? asset('images/events/' . $event->gambar)
                  : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
              class="w-full h-96 object-cover"
              alt="Gambar Event"
            />
          </figure>

          <div class="card-body">
            <h1 class="text-3xl font-bold">{{ $event->judul }}</h1>
            <p class="text-sm text-gray-500">
              {{ \Carbon\Carbon::parse($event->tanggal_waktu)->translatedFormat('d F Y, H:i') }}
              • {{ $event->lokasi }}
            </p>

            <div class="divider"></div>
            <h3 class="text-xl font-bold">Pilih Tiket</h3>

            @foreach($event->tikets as $tiket)
              <div class="card card-side shadow-sm p-4 items-center">
                <div class="flex-1">
                  <h4 class="font-bold">{{ $tiket->ticketType->nama }}</h4>
                  <p class="text-sm">Stok: {{ $tiket->stok }}</p>
                </div>

                <div class="w-44 text-right">
                  <div class="font-bold">
                    {{ $tiket->harga ? 'Rp '.number_format($tiket->harga,0,',','.') : 'Gratis' }}
                  </div>

                  <div class="mt-2 flex justify-end gap-2">
                    <button data-action="dec" data-id="{{ $tiket->id }}" class="btn btn-sm">−</button>
                    <input id="qty-{{ $tiket->id }}" value="0" type="number"
                      class="input w-16 text-center">
                    <button data-action="inc" data-id="{{ $tiket->id }}" class="btn btn-sm">+</button>
                  </div>

                  <div class="text-sm mt-2">
                    Subtotal: <span id="subtotal-{{ $tiket->id }}">Rp 0</span>
                  </div>
                </div>
              </div>
            @endforeach

          </div>
        </div>
      </div>

      {{-- ===================== RIGHT ===================== --}}
      <aside>
        <div class="card p-4 shadow sticky top-24">
          <h4 class="font-bold">Ringkasan</h4>

          <div class="mt-2 flex justify-between">
            <span>Item</span><span id="summaryItems">0</span>
          </div>

          <div class="flex justify-between font-bold">
            <span>Total</span><span id="summaryTotal">Rp 0</span>
          </div>

          @auth
            <button id="checkoutButton" onclick="openCheckout()" disabled
              class="btn btn-primary mt-4">
              Checkout
            </button>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary mt-4">Login</a>
          @endauth
        </div>
      </aside>
    </div>

    {{-- ===================== MODAL CHECKOUT ===================== --}}
    <dialog id="checkout_modal" class="modal">
      <div class="modal-box">
        <h3 class="font-bold">Konfirmasi Pesanan</h3>

        <div id="modalItems" class="mt-3"></div>

        <div class="divider"></div>

        <div class="flex justify-between font-bold">
          <span>Total</span><span id="modalTotal">Rp 0</span>
        </div>

        {{-- PILIH PAYMENT --}}
        <div class="form-control mt-4">
          <label class="label">
            <span class="label-text font-semibold">Metode Pembayaran</span>
          </label>
          <select id="payment_type" class="select select-bordered w-full">
            <option value="" disabled selected>Pilih Metode Pembayaran</option>
            @foreach ($paymentTypes as $type)
              <option value="{{ $type->id }}">{{ $type->nama }}</option>
            @endforeach
          </select>
        </div>

        <div class="modal-action">
          <button class="btn"
            onclick="document.getElementById('checkout_modal').close()">Batal</button>
          <button id="confirmCheckout" class="btn btn-primary">
            Konfirmasi
          </button>
        </div>
      </div>
    </dialog>

  </section>

  {{-- ===================== JAVASCRIPT ===================== --}}
  <script>
  (() => {
    const format = n => 'Rp ' + Number(n).toLocaleString('id-ID');

    const tickets = {
      @foreach($event->tikets as $tiket)
      {{ $tiket->id }}: {
        id: {{ $tiket->id }},
        price: {{ $tiket->harga ?? 0 }},
        stock: {{ $tiket->stok }},
        name: "{{ e($tiket->ticketType->nama) }}"
      },
      @endforeach
    };

    const summaryItems = document.getElementById('summaryItems');
    const summaryTotal = document.getElementById('summaryTotal');
    const checkoutBtn = document.getElementById('checkoutButton');
    const modal = document.getElementById('checkout_modal');
    const confirmBtn = document.getElementById('confirmCheckout');

    function update() {
      let qty = 0, total = 0;
      Object.values(tickets).forEach(t => {
        const v = +document.getElementById('qty-'+t.id).value;
        if (v > 0) {
          qty += v;
          total += v * t.price;
          document.getElementById('subtotal-'+t.id).textContent = format(v*t.price);
        }
      });
      summaryItems.textContent = qty;
      summaryTotal.textContent = format(total);
      checkoutBtn.disabled = qty === 0;
    }

    document.querySelectorAll('[data-action]').forEach(btn => {
      btn.onclick = () => {
        const id = btn.dataset.id;
        const input = document.getElementById('qty-'+id);
        let v = +input.value;
        if (btn.dataset.action === 'inc' && v < tickets[id].stock) v++;
        if (btn.dataset.action === 'dec' && v > 0) v--;
        input.value = v;
        update();
      }
    });

    window.openCheckout = () => {
      let html = '', total = 0;
      Object.values(tickets).forEach(t => {
        const v = +document.getElementById('qty-'+t.id).value;
        if (v > 0) {
          html += `<div class="flex justify-between">
            <span>${t.name} x ${v}</span>
            <span>${format(v*t.price)}</span>
          </div>`;
          total += v * t.price;
        }
      });
      document.getElementById('modalItems').innerHTML = html;
      document.getElementById('modalTotal').textContent = format(total);
      modal.showModal();
    };

    confirmBtn.onclick = async () => {
      const paymentTypeId = document.getElementById('payment_type').value;
      if (!paymentTypeId) {
        alert('Silakan pilih metode pembayaran');
        return;
      }

      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Memproses...';

      const items = Object.values(tickets)
        .map(t => ({ tiket_id: t.id, jumlah: +document.getElementById('qty-'+t.id).value }))
        .filter(i => i.jumlah > 0);

      try {
        const res = await fetch("{{ route('orders.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({
            event_id: {{ $event->id }},
            payment_type_id: paymentTypeId,
            items
          })
        });

        const data = await res.json();
        modal.close();
        window.location.href = data.redirect;
      } catch {
        alert('Gagal checkout');
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Konfirmasi';
      }
    };

    update();
  })();
  </script>
</x-layouts.app>
