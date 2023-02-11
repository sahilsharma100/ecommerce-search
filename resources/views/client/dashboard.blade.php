{{-- Extends layout --}}
@extends('layouts.app')

{{-- Content --}}
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
            <div class="mr-auto d-none d-lg-block">
                <h3 class="text-black font-w600">Welcome to</h3>
                <p class="mb-0 fs-18">Cient Dashboard Template</p>
            </div>

            <form action="{{ route('client.dashboard') }}" method="get">
                <div class="input-group search-area ml-auto d-inline-flex">
                    <input type="text" class="form-control" name="search" placeholder="Search Product" value="{{ $search }}">
                    <div class="input-group-append">
                        <button type="submit" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                    </div>
                </div>
            </form>

        </div>
        <div class="page-titles">
            <ol class="breadcrumb p-2">
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
            </ol>
        </div>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-lg-12 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row m-b-30">
                                <div class="col-md-5 col-xxl-12">
                                    <div class="new-arrival-product mb-4 mb-xxl-4 mb-md-0">
                                        <div class="new-arrivals-img-contnent">
                                            <a href="/{{ $product->image->path }}{{ $product->image->file }}"
                                                target="blank">
                                                <img loading="lazy" class="img-fluid"
                                                    src="/{{ $product->image->path }}{{ $product->image->file }}"
                                                    width="40px" alt="{{ $product->title }}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 col-xxl-12">
                                    <div class="new-arrival-content position-relative">
                                        <h4>
                                            {{ $product->title }}
                                        </h4>
                                        <p class="price">$320.00</p>
                                        <p>Availability: <span class="item"> In stock <i
                                                    class="fa fa-check-circle text-success"></i></span></p>
                                        <p>Product code: <span class="item">0405689</span> </p>
                                        <p>
                                            Category:
                                            @forelse ($product->categories as $category)
                                                <span class="item">{{ $category->title }}</span>
                                            @empty
                                                "N/A"
                                            @endforelse
                                        </p>
                                        <p class="text-content">
                                            {{ $product->description }}
                                        </p>
                                        <div class="comment-review star-rating text-right">
                                            <ul>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star"></i></li>
                                                <li><i class="fa fa-star-half-empty"></i></li>
                                                <li><i class="fa fa-star-half-empty"></i></li>
                                            </ul>
                                            <span class="review-text">(34 reviews) / </span><a class="product-review"
                                                href="javascript:void(0);">Write a review?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{ $products->links('partials.pagination') }}
        </div>
    </div>
@endsection

@push('js')
@endpush
