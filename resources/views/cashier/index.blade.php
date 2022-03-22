@extends('layout.template')
@section('title', $title)
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Kasir</h2>
    </div>
    <div class="row">
        <div class="col-md mb-3 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-6 col-md-4 mb-3">
                                <a role="button" class="text-decoration-none text-black btn-product" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($product->image)
                                                <img src="{{ asset('img/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ asset('img/default.png') }}" class="card-img-top" alt="{{ $product->name }}">
                                            @endif
                                            <h5 class="card-title m-0">{{ $product->name }}</h5>
                                            <small class="card-text">Rp {{ number_format($product->price) }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body">
                    <h4 class="border-bottom py-1">Transaksi</h4>
                    <form>
                        @csrf
                        <div class="row">
                            <div class="col fw-bold">
                                Produk
                            </div>
                            <div class="col fw-bold">
                                Jumlah
                            </div>
                        </div>
                        <div id="form-input"></div>
                        <hr>
                        <button type="submit" class="btn btn-success w-100">Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $('form').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('cashier.create') }}",
                    method: "POST",
                    data: formData,
                    success: function(res) {
                        Swal.fire({
                            title: 'Rp ' + res.totalFormat,
                            input: 'text',
                            preConfirm: (value) => {
                                Swal.fire({
                                    title: 'Kembalian',
                                    text: 'Rp ' + (value - res.total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });
                    },
                    error: function(err) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $('.btn-product').click(function() {
                var html = '<div class="input-group mb-1"><input type="hidden" class="form-control" name="product_id[]" value="' + $(this).data('id') + '"><input type="text" class="form-control" value="' + $(this).data('name') + '" disabled><input type="number" class="form-control" name="quantity[]" value="1"><button type="button" class="btn btn-danger btn-remove">HAPUS</button></div>';
                $('#form-input').append(html);
            });

            $('#form-input').on('click', '.btn-remove', function() {
                $(this).parent().remove();
            });
        </script>
    @endpush
@endsection