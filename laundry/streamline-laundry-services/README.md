# Streamline Laundry Services

Welcome to the Streamline Laundry Services online booking system! This project provides a user-friendly interface for customers to manage their laundry services, including booking, registration, and account management.

## Features

- User Registration
- User Login
- Forgot Password and Reset Password functionality
- Booking Page for selecting laundry services
- History Page for viewing transactions made

## Project Structure

```
streamline-laundry-services
├── src
│   ├── css
│   │   ├── styles.css          # Styles for the application
│   ├── js
│   │   ├── main.js             # Main JavaScript entry point
│   │   ├── login.js            # Logic for the login page
│   │   ├── forgot-password.js   # Functionality for forgot password
│   │   ├── reset-password.js    # Reset password logic
│   │   ├── registration.js      # Registration page logic
│   │   ├── booking.js           # Booking process management
│   │   └── invoice.js           # Invoice generation and display
│   ├── pages
│   │   ├── index.html           # Homepage
│   │   ├── login.html           # Login form
│   │   ├── forgot-password.html  # Forgot password page
│   │   ├── reset-password.html   # Reset password form
│   │   ├── registration.html     # Registration form
│   │   ├── booking.html          # Booking page
│   │   └── invoice.html          # Invoice display page
├── README.md                     # Project documentation
└── package.json                  # npm configuration file
```

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd streamline-laundry-services
   ```

3. Install dependencies:
   ```
   npm install
   ```

## Usage

- Open `src/pages/index.html` in your browser to access the application.
- Follow the prompts to register, log in, book services, and view invoices.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any suggestions or improvements.

## License

This project is licensed under the MIT License.

=========== CHANGES ============

## Changes

- set first the env/.env file for sending of reset link

- the invoice page now serves as the completion page which only receive 1 transaction at a time, after booking a specific service it will be redirected to the invoice page and the user will need to pay it.

- if by any chance the user accidentally click back to previous page and click the invoice page again it will retrieve no value so this is where the history page will enter.

- the booking page is booking 1 service per transaction as per the original setup of the system.

- login, registration, forgot password and resetting of password are all working properly.

- there's also a session security where only logged users are the one who can access the booking, transaction history and invoice


## Suggestions
- for the history page you need to have a table of transactions for the user it will have column names of (Invoice No., Service, booking date, price and status), in the status column this is where the user can check if the transactions paid or unpaid, make it clickable if its unpaid so they can be redirected to the payment.

- for easier setup just add another table column name status in the existing database table called 'services'

- if you have a web hosting you will need to update the forgot_logic php because for this setup I only used the phpmailer which used a gmail proxy for sending reset link


## .env setup
- create a dummy gmail account
- after successful creation of gmail account, search in google generate app password and generate app password
- now copy the app password paste it in the env file 


## Algorithm Used
- Hashing algorithm - used by php password_hash, it hashes the password in user registration
- Sorting algorithms - mostly used because of insert and fetching of data in the database


## Database creation XAMPP Control Panel
- create a database first named 'stream_laundry'
- after that just import the sql provided inside the folder 