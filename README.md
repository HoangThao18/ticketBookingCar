## Introduction

This API allows you to manage various aspects of a ticketing system for bus tickets.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Base URL

-   https://ticketpro.deece.vn/api

## Endpoints

## Authentication

-   POST /login: Logs a user in.
-   POST /forgot-password: Sends a reset password link to the user's email.
-   POST /reset-password: Resets user's password.
-   POST /register: Registers a new user.
-   GET /login/{provider}: Initiates OAuth authentication with a provider.
-   GET /login/{provider}/callback: Handles OAuth callback.

## Trips

-   GET /trip/search: Searches for trips.
-   GET /trip/popular: Gets popular trips.
-   GET /trip/{trip}: Gets details of a specific trip.

## Stations

-   GET /station/province: Gets provinces with stations.
-   GET /station: Lists all stations.

## News

-   GET /news: Lists all news.
-   GET /news/popular: Gets popular news.
-   GET /news/lastest: Gets latest news.
-   GET /news/{id}: Gets details of a specific news article.

## Comments

-   GET /car/{id}/comments: Gets comments for a specific car.

## Checkout

-   GET /vnpay-return: Handles VNPAY return callback.
-   POST /vnpay-payment: Initiates VNPAY payment.
-   POST /getbankqr: Gets bank QR code for payment.
-   GET /bank-return: Handles bank return callback.
-   POST /send-order-confirmation: Sends order confirmation email.

## User Management

-   GET /user/logout: Logs the user out.
-   GET /user/profile: Gets user's profile.
-   PUT /user/cancel-booking: Cancels a booking.
-   PUT /user: Updates user's information.
-   PUT /user/change-password: Changes user's password.
-   POST /user/update: Updates user's information.
-   POST /user/comment: Adds a comment.
