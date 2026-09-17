@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <section class="hero">
        <h1>Contact Us</h1>
        <p class="hero-subtitle">We would love to hear from you. Reach out any time.</p>
    </section>

    <section class="section">
        <h2>Contact Details</h2>
        <div class="card contact-card">
            <ul class="contact-list">
                <li>
                    <span class="label">Email</span>
                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                </li>
                <li>
                    <span class="label">Phone</span>
                    <a href="tel:{{ str_replace(' ', '', $phone) }}">{{ $phone }}</a>
                </li>
                <li>
                    <span class="label">Address</span>
                    <span>{{ $address }}</span>
                </li>
            </ul>
        </div>
    </section>
@endsection