<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MediCare Hospital - Reception Staff Registration</title>
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
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
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
        
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #e67e22;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #e67e22;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
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
            <span class="logo">💼</span>
            <div class="hospital-name">MediCare Hospital</div>
            <div class="welcome-text">Welcome to Our Front Desk Team</div>
        </div>

        <div class="content">
            <h2 style="margin-top: 0; color: #e67e22;">Dear {{ $reception->name }},</h2>
            
            <p>Welcome to MediCare Hospital! We're excited to have you join our reception team. As the first point of contact for our patients, you play a crucial role in creating a positive and welcoming environment.</p>
            
            <div class="user-info">
                <h3 style="color: #e67e22; margin-top: 0;">Your Account Details:</h3>
                <div class="info-item">
                    <span class="info-label">Full Name:</span> {{ $reception->name }}
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span> {{ $reception->email }}
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span> {{ $reception->phone }}
                </div>
                @if($reception->employee_id)
                <div class="info-item">
                    <span class="info-label">Employee ID:</span> {{ $reception->employee_id }}
                </div>
                @endif
                @if($reception->shift_timing)
                <div class="info-item">
                    <span class="info-label">Shift Timing:</span> {{ $reception->shift_timing }}
                </div>
                @endif
            </div>

            <p>Your reception staff account has been successfully created and you can now access our hospital management system. Through the reception portal, you can:</p>
            
            <ul>
                <li>Manage patient appointments and scheduling</li>
                <li>Register new patients</li>
                <li>Handle billing and payments</li>
                <li>Coordinate with doctors and medical staff</li>
                <li>Manage room allocations</li>
                <li>Generate reports and maintain records</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="cta-button">Access Reception Portal</a>
            </div>

            <div class="contact-info">
                <h4 style="margin-top: 0; color: #e67e22;">Important Notes:</h4>
                <p>Please report to the Front Office Manager on your first day for orientation and training.</p>
                <p>📞 Front Office: +91 8778636729<br>
                   📧 Email: mn1019088@gmail.com<br>
                   🏥 Address: MTH Road, Tidel Park 3, Pattabiram, Chennai, Tamil Nadu, 600072.</p>
            </div>

            <p>We're confident that your skills and positive attitude will greatly benefit our patients and the entire hospital team.</p>

            <p>Best regards,<br>
            <strong>The MediCare Hospital Administration Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} MediCare Hospital. All rights reserved.</p>
            <p>This email was sent to {{ $reception->email }} as part of your staff registration at MediCare Hospital.</p>
            <p><a href="#" style="color: #e67e22;">Privacy Policy</a> | <a href="#" style="color: #e67e22;">Terms of Service</a></p>
        </div>
    </div>
</body>
</html>