<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MediCare Hospital</title>
<style>
    body, table, td, a {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }
    table, td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }
    img {
        -ms-interpolation-mode: bicubic;
        border: 0;
        height: auto;
        line-height: 100%;
        outline: none;
        text-decoration: none;
    }
    p, a, li, td, blockquote {
        mso-line-height-rule: exactly;
    }
    
    /* Main styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333333;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
        font-size: 16px;
    }
    
    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .header {
        background: linear-gradient(135deg, #2c5aa0 0%, #1e3d72 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .logo {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: block;
    }
    
    .hospital-name {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .welcome-text {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .content {
        padding: 30px;
    }
    
    .patient-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
        border-left: 4px solid #8e44ad;
    }
    
    .info-item {
        margin-bottom: 10px;
    }
    
    .info-label {
        font-weight: bold;
        color: #2c5aa0;
    }
    
    .cta-button {
        display: inline-block;
        background: linear-gradient(135deg, #2c5aa0 0%, #1e3d72 100%);
        color: white;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 25px;
        font-weight: bold;
        margin: 20px 0;
        text-align: center;
    }
    
    .footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .contact-info {
        background: #e8f4fd;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    @media only screen and (max-width: 600px) {
        .container {
            width: 100% !important;
            border-radius: 0;
        }
        
        .content {
            padding: 20px !important;
        }
        
        .header {
            padding: 20px !important;
        }
        
        .hospital-name {
            font-size: 1.5rem !important;
        }
        
        .welcome-text {
            font-size: 1rem !important;
        }
    }
</style>
</head>
<body>
    
    <div class="container">
        <div class="header">
            <span class="logo">🏥</span>
            <div class="hospital-name">MediCare Hospital</div>
            <div class="welcome-text">Welcome to Our Healthcare Family</div>
        </div>

        <div class="content">
            <h2 style="margin-top: 0; color: #2c5aa0;">Dear {{ $patient->first_name }} {{ $patient->last_name }},</h2>
            
            <p>Welcome to MediCare Hospital! We're delighted to have you as part of our healthcare community. Our team is committed to providing you with exceptional medical care and support.</p>
            
            <div class="patient-info">
                <h3 style="color: #8e44ad; margin-top: 0;">Your Registration Details:</h3>
                <div class="info-item">
                    <span class="info-label">Patient ID:</span> {{ $patient->patient_id }}
                </div>
                <div class="info-item">
                    <span class="info-label">Full Name:</span> {{ $patient->first_name }} {{ $patient->last_name }}
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span> {{ $patient->email }}
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span> {{ $patient->phone }}
                </div>
                @if($patient->date_of_birth)
                <div class="info-item">
                    <span class="info-label">Date of Birth:</span> {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}
                </div>
                @endif
            </div>

            <p>Your account has been successfully created and you can now access our healthcare services through our patient portal. Through the portal, you can:</p>
            
            <ul>
                <li>Schedule appointments</li>
                <li>View your medical records</li>
                <li>Communicate with your healthcare providers</li>
                <li>Access test results</li>
                <li>Manage your prescriptions</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="cta-button">Access Your Patient Portal</a>
            </div>

            <div class="contact-info">
                <h4 style="margin-top: 0; color: #2c5aa0;">Need Help?</h4>
                <p>If you have any questions or need assistance, please don't hesitate to contact us:</p>
                <p>📞 Phone: +91 8778636729<br>
                   📧 Email: manjunathan04@gmail.com<br>
                   🏥 Address: MTH Road, Tidel Park 3, Pattabiram, Chennai, Tamil Nadu, 600072.</p>
            </div>

            <p>We're committed to providing you with the best healthcare experience. Thank you for choosing MediCare Hospital!</p>

            <p>Best regards,<br>
            <strong>The MediCare Hospital Administration Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} MediCare Hospital. All rights reserved.</p>
            <p>This email was sent to {{ $patient->email }} because you registered at MediCare Hospital.</p>
            <p><a href="#" style="color: #2c5aa0;">Privacy Policy</a> | <a href="#" style="color: #2c5aa0;">Terms of Service</a></p>
        </div>
    </div>
</body>
</html>