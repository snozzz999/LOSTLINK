# VU LostLink

VU LostLink is a web-based lost and found management system designed for university environments.  
The platform allows users to report lost items, submit found items, and securely verify ownership through an admin verification process.

The system aims to improve the efficiency of campus lost-and-found management by providing a centralized digital platform for reporting and verifying items.

This project was developed as part of a university software development project.

---

## Live Website

https://vulostlink.42web.io

---

## Features

### User Features
- Secure user registration and login
- Email-based OTP authentication
- Report lost items
- Report found items
- Upload item images
- View personal item reports
- Respond to admin verification questions
- Receive notifications and email updates
- Search and browse reported items
- Built-in support chatbot

### Admin Features
- Review submitted lost and found items
- Send verification questions to users
- Approve or reject claims
- Request additional verification information
- Manage users
- Remove incorrect or fraudulent reports

---

## System Architecture

The system follows a standard **client-server architecture**.

User Browser
|
v
Frontend (HTML / CSS / Bootstrap / JavaScript)
|
v
PHP Application Server
|
v
MySQL Database


---

## Technologies Used

### Frontend
- HTML5
- CSS3
- Bootstrap 5
- JavaScript

### Backend
- PHP
- MySQL
- PHPMailer (for email services)

### Infrastructure
- XAMPP (local development)
- InfinityFree (deployment hosting)
- GitHub (version control)

---

## Database Structure

Main tables used in the system:


---

## Technologies Used

### Frontend
- HTML5
- CSS3
- Bootstrap 5
- JavaScript

### Backend
- PHP
- MySQL
- PHPMailer (for email services)

### Infrastructure
- XAMPP (local development)
- InfinityFree (deployment hosting)
- GitHub (version control)

---

## Database Structure

Main tables used in the system:
users
lost_items
verifications
notification

### Key Table Purposes

| Table | Purpose |
|------|--------|
| users | Stores registered user accounts |
| lost_items | Stores lost and found reports |
| verifications | Handles admin verification questions and responses |
| notifications | Stores user notifications |


---

## Deployment

The application is deployed using **InfinityFree hosting**.

Deployment steps:
1. Upload project files to `htdocs`
2. Import MySQL database
3. Update database credentials in `config.php`
4. Configure SMTP email settings
5. Access the live website

---

## Security Features

- OTP-based login verification
- Email verification
- Admin moderation of item claims
- Limited public information visibility
- Secure image upload validation
- Session-based authentication

---

## Known Limitations

- Image uploads limited to 2MB due to hosting restrictions
- System designed primarily for university campus environments
- Item matching is currently manual and performed by administrators

---

## Future Improvements

- Mobile application support
- Image recognition for lost items
- Advanced item matching suggestions
- Real-time notifications
- QR code tagging for items
- Location-based reporting

---

## Contributors

Project developed collaboratively by the team.

All members contributed equally to system design, development, testing, and documentation.

One team member, Rohan, was unfortunately unable to participate fully due to a medical operation during the project period.

---

## License

This project is developed for educational purposes.




