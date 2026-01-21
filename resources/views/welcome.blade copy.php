<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7 Kebiasaan Anak Indonesia Hebat</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --accent: #f093fb;
            --white: #ffffff;
            --text: #333333;
            --light-text: #666666;
        }

        body {
            font-family: 'Fredoka', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            height: 60px;
        }

        .logo-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-text .logo {
            font-size: clamp(1.5rem, 4vw, 1.8rem);
            animation: bounce 2s infinite;
        }

        .logo-text h1 {
            font-size: clamp(0.8rem, 3vw, 1rem);
            color: var(--text);
            font-weight: 700;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            gap: clamp(0.5rem, 2vw, 0.8rem);
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            padding: clamp(0.3rem, 1vw, 0.4rem) clamp(0.6rem, 2vw, 0.8rem);
            border-radius: 15px;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-size: clamp(0.75rem, 2.5vw, 0.85rem);
        }

        .nav-links a:hover {
            background: var(--primary);
            color: white;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--text);
        }

        /* Main content spacing for header */
        .main-content {
            padding-top: 70px;
            min-height: calc(100vh - 70px);
            width: 100%;
        }

        /* Footer Styles */
        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: clamp(1rem, 3vw, 1.5rem);
            text-align: center;
            width: 100%;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: clamp(1rem, 3vw, 1.5rem);
            text-align: left;
        }

        .footer-section h3 {
            font-size: clamp(1rem, 3vw, 1.1rem);
            margin-bottom: 0.8rem;
            color: var(--accent);
        }

        .footer-section p {
            line-height: 1.5;
            margin-bottom: 0.8rem;
            font-size: clamp(0.8rem, 2.5vw, 0.9rem);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.4rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: clamp(0.8rem, 2.5vw, 0.9rem);
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .social-icons {
            display: flex;
            gap: 0.8rem;
            margin-top: 0.8rem;
            flex-wrap: wrap;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: clamp(30px, 8vw, 35px);
            height: clamp(30px, 8vw, 35px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            font-size: clamp(0.9rem, 3vw, 1rem);
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            font-size: clamp(0.7rem, 2.5vw, 0.8rem);
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 100px;
            animation: float 20s infinite linear;
        }

        .cloud:nth-child(1) {
            width: clamp(100px, 25vw, 150px);
            height: clamp(30px, 8vw, 45px);
            top: 15%;
            left: -150px;
            animation-delay: 0s;
        }

        .cloud:nth-child(2) {
            width: clamp(80px, 20vw, 120px);
            height: clamp(25px, 7vw, 40px);
            top: 35%;
            left: -120px;
            animation-delay: 5s;
        }

        .cloud:nth-child(3) {
            width: clamp(90px, 22vw, 140px);
            height: clamp(28px, 7.5vw, 42px);
            top: 55%;
            left: -140px;
            animation-delay: 10s;
        }

        .cloud:nth-child(4) {
            width: clamp(110px, 28vw, 170px);
            height: clamp(35px, 9vw, 50px);
            top: 75%;
            left: -170px;
            animation-delay: 15s;
        }

        @keyframes float {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(100vw + 200px)); }
        }

        .stars {
            position: absolute;
            width: clamp(2px, 1vw, 3px);
            height: clamp(2px, 1vw, 3px);
            background: white;
            border-radius: 50%;
            animation: twinkle 2s infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.3); }
        }

        /* Floating Shapes */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float-shape 15s infinite ease-in-out;
        }

        .shape-1 {
            width: clamp(50px, 15vw, 80px);
            height: clamp(50px, 15vw, 80px);
            background: #ff9a9e;
            top: 15%;
            left: 15%;
            animation-delay: 0s;
        }

        .shape-2 {
            width: clamp(80px, 20vw, 120px);
            height: clamp(80px, 20vw, 120px);
            background: #fad0c4;
            top: 65%;
            left: 85%;
            animation-delay: 2s;
        }

        .shape-3 {
            width: clamp(40px, 12vw, 60px);
            height: clamp(40px, 12vw, 60px);
            background: #a1c4fd;
            top: 85%;
            left: 25%;
            animation-delay: 4s;
        }

        @keyframes float-shape {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Container */
        .container {
            position: relative;
            z-index: 10;
            max-width: min(1200px, 95vw);
            margin: 0 auto;
            padding: clamp(0.8rem, 3vw, 1rem);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Welcome Header */
        .welcome-header {
            text-align: center;
            margin-bottom: clamp(1.5rem, 5vw, 2rem);
            animation: slideDown 1s ease;
            width: 100%;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            margin-bottom: clamp(1rem, 4vw, 1.5rem);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .logo {
            font-size: clamp(2.5rem, 15vw, 4rem);
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
        }

        .title {
            font-size: clamp(1.5rem, 8vw, 2.2rem);
            font-weight: 700;
            color: white;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: clamp(0.6rem, 3vw, 0.8rem);
            line-height: 1.2;
        }

        .subtitle {
            font-size: clamp(0.9rem, 4vw, 1.1rem);
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            margin-bottom: clamp(0.6rem, 3vw, 0.8rem);
        }

        .badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            padding: clamp(0.5rem, 2vw, 0.6rem) clamp(1rem, 4vw, 1.5rem);
            border-radius: 40px;
            color: white;
            font-weight: 600;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
            border: 2px solid rgba(255, 255, 255, 0.5);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }

        /* Habits Grid */
        .habits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(240px, 90vw), 1fr));
            gap: clamp(1rem, 4vw, 1.5rem);
            width: 100%;
            margin-bottom: clamp(1.5rem, 5vw, 2rem);
        }

        .habit-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            padding: clamp(1rem, 4vw, 1.5rem);
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
        }

        .habit-card:nth-child(1) { animation-delay: 0.1s; }
        .habit-card:nth-child(2) { animation-delay: 0.2s; }
        .habit-card:nth-child(3) { animation-delay: 0.3s; }
        .habit-card:nth-child(4) { animation-delay: 0.4s; }
        .habit-card:nth-child(5) { animation-delay: 0.5s; }
        .habit-card:nth-child(6) { animation-delay: 0.6s; }
        .habit-card:nth-child(7) { animation-delay: 0.7s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .habit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            transition: left 0.5s;
        }

        .habit-card:hover::before {
            left: 100%;
        }

        .habit-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .habit-number {
            position: absolute;
            top: clamp(0.6rem, 2vw, 0.8rem);
            right: clamp(0.6rem, 2vw, 0.8rem);
            width: clamp(35px, 10vw, 40px);
            height: clamp(35px, 10vw, 40px);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: clamp(1rem, 3vw, 1.2rem);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .habit-icon {
            font-size: clamp(2.5rem, 10vw, 3.5rem);
            margin-bottom: clamp(0.6rem, 3vw, 0.8rem);
            animation: wobble 3s infinite;
            filter: drop-shadow(0 3px 8px rgba(0, 0, 0, 0.1));
        }

        @keyframes wobble {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-3deg); }
            75% { transform: rotate(3deg); }
        }

        .habit-title {
            font-size: clamp(1rem, 4vw, 1.2rem);
            font-weight: 700;
            color: var(--text);
            margin-bottom: clamp(0.3rem, 2vw, 0.4rem);
        }

        .habit-description {
            font-size: clamp(0.8rem, 3vw, 0.9rem);
            color: var(--light-text);
            line-height: 1.4;
        }

        /* CTA Button */
        .cta-container {
            text-align: center;
            animation: fadeIn 1s ease 1s forwards;
            opacity: 0;
            margin-top: clamp(0.8rem, 3vw, 1rem);
            width: 100%;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .cta-button {
            display: inline-block;
            background: white;
            color: var(--primary);
            padding: clamp(0.8rem, 3vw, 1rem) clamp(1.5rem, 6vw, 2.5rem);
            border-radius: 40px;
            font-size: clamp(1rem, 4vw, 1.1rem);
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .cta-button:hover::before {
            width: min(250px, 80vw);
            height: min(250px, 80vw);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .cta-button span {
            position: relative;
            z-index: 1;
        }

        /* Mascot */
        .mascot {
            position: fixed;
            bottom: clamp(1rem, 4vw, 1.5rem);
            right: clamp(1rem, 4vw, 1.5rem);
            font-size: clamp(3rem, 10vw, 5rem);
            animation: float-mascot 3s ease-in-out infinite;
            z-index: 100;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
            cursor: pointer;
            transition: transform 0.3s;
        }

        .mascot:hover {
            transform: scale(1.05);
        }

        @keyframes float-mascot {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(3deg); }
        }

        /* Rocket Loading Screen */
        .rocket-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 1s ease;
        }

        .rocket-container {
            position: relative;
            width: clamp(100px, 30vw, 150px);
            height: clamp(140px, 40vw, 220px);
            margin-bottom: clamp(1rem, 4vw, 1.5rem);
        }

        .rocket {
            font-size: clamp(3rem, 15vw, 5rem);
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation: rocket-idle 2s ease-in-out infinite;
            cursor: pointer;
            transition: all 0.5s ease;
            filter: drop-shadow(0 3px 10px rgba(0, 0, 0, 0.2));
            z-index: 2;
        }

        .rocket:hover {
            transform: translateX(-50%) scale(1.05);
        }

        .rocket.launch {
            animation: rocket-launch 4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .rocket-trail {
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 0;
            background: linear-gradient(to top, #ff9a00, #ff3d00, transparent);
            border-radius: 50% 50% 0 0;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .rocket.launch .rocket-trail {
            height: 80px;
            opacity: 0.7;
            animation: trail-expand 4s forwards;
        }

        .smoke {
            position: absolute;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            filter: blur(5px);
            opacity: 0;
            z-index: 1;
        }

        .rocket.launch .smoke {
            animation: smoke-trail 4s forwards;
        }

        .explosion {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 0;
            opacity: 0;
            z-index: 10000;
        }

        .explosion.active {
            animation: explosion-animation 1.2s ease-out forwards;
        }

        .loading-text {
            color: white;
            font-size: clamp(1.2rem, 5vw, 1.5rem);
            font-weight: 700;
            margin-top: clamp(1rem, 4vw, 1.5rem);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .click-instruction {
            color: rgba(255, 255, 255, 0.8);
            font-size: clamp(0.8rem, 3.5vw, 1rem);
            margin-top: clamp(0.6rem, 3vw, 0.8rem);
            text-align: center;
            animation: pulse 2s infinite;
        }

        @keyframes rocket-idle {
            0%, 100% { transform: translateX(-50%) translateY(0) rotate(0deg); }
            25% { transform: translateX(-50%) translateY(-3px) rotate(1deg); }
            50% { transform: translateX(-50%) translateY(-6px) rotate(0deg); }
            75% { transform: translateX(-50%) translateY(-3px) rotate(-1deg); }
        }

        @keyframes rocket-launch {
            0% { 
                transform: translateX(-50%) translateY(0) rotate(0deg);
                opacity: 1;
            }
            15% {
                transform: translateX(-30%) translateY(-15vh) rotate(5deg);
                opacity: 1;
            }
            30% {
                transform: translateX(20%) translateY(-30vh) rotate(-8deg);
                opacity: 1;
            }
            45% {
                transform: translateX(-25%) translateY(-45vh) rotate(10deg);
                opacity: 1;
            }
            60% {
                transform: translateX(15%) translateY(-60vh) rotate(-12deg);
                opacity: 1;
            }
            75% {
                transform: translateX(-10%) translateY(-75vh) rotate(8deg);
                opacity: 0.9;
            }
            85% {
                transform: translateX(5%) translateY(-85vh) rotate(-5deg);
                opacity: 0.7;
            }
            95% {
                transform: translateX(0) translateY(-95vh) rotate(0deg) scale(1.3);
                opacity: 0.5;
            }
            100% { 
                transform: translateX(0) translateY(-100vh) rotate(0deg) scale(1.5);
                opacity: 0;
            }
        }

        @keyframes trail-expand {
            0% { height: 0; opacity: 0; }
            20% { height: 60px; opacity: 0.7; }
            40% { height: 90px; opacity: 0.5; }
            60% { height: 70px; opacity: 0.3; }
            80% { height: 40px; opacity: 0.1; }
            100% { height: 0; opacity: 0; }
        }

        @keyframes smoke-trail {
            0% { 
                transform: translateY(0) scale(0);
                opacity: 0;
            }
            20% {
                transform: translateY(-20px) scale(1);
                opacity: 0.7;
            }
            40% {
                transform: translateY(-40px) scale(1.2);
                opacity: 0.5;
            }
            60% {
                transform: translateY(-60px) scale(1.4);
                opacity: 0.3;
            }
            80% {
                transform: translateY(-80px) scale(1.6);
                opacity: 0.1;
            }
            100% {
                transform: translateY(-100px) scale(1.8);
                opacity: 0;
            }
        }

        @keyframes explosion-animation {
            0% {
                font-size: 0;
                opacity: 0;
                transform: translate(-50%, -50%) scale(0);
            }
            20% {
                font-size: 3rem;
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            40% {
                font-size: 4.5rem;
                opacity: 0.8;
                transform: translate(-50%, -50%) scale(1.2);
            }
            60% {
                font-size: 6rem;
                opacity: 0.6;
                transform: translate(-50%, -50%) scale(1.4);
            }
            80% {
                font-size: 7rem;
                opacity: 0.4;
                transform: translate(-50%, -50%) scale(1.6);
            }
            100% {
                font-size: 8rem;
                opacity: 0;
                transform: translate(-50%, -50%) scale(1.8);
            }
        }

        /* Journal Section - MODIFIED: Now appears below main content */
        .journal-section {
            display: none;
            position: relative;
            z-index: 10;
            max-width: min(1200px, 95vw);
            margin: 3rem auto;
            padding: clamp(1rem, 4vw, 2rem);
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeInScale 0.8s ease;
            width: 100%;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .journal-header {
            text-align: center;
            margin-bottom: 2rem;
            width: 100%;
        }

        .journal-title {
            font-size: clamp(1.3rem, 6vw, 2.5rem);
            color: var(--text);
            margin-bottom: 1rem;
        }

        .journal-subtitle {
            font-size: clamp(0.9rem, 3.5vw, 1.2rem);
            color: var(--light-text);
        }

        .journal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(280px, 90vw), 1fr));
            gap: clamp(1rem, 4vw, 2rem);
            width: 100%;
            margin-bottom: 2rem;
        }

        .journal-card {
            background: white;
            border-radius: 20px;
            padding: clamp(1rem, 4vw, 1.5rem);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .journal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            border-color: var(--primary);
        }

        .journal-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .journal-card-icon {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            margin-right: 1rem;
        }

        .journal-card-title {
            font-size: clamp(1rem, 3.5vw, 1.3rem);
            color: var(--text);
            font-weight: 600;
        }

        .journal-card-description {
            color: var(--light-text);
            margin-bottom: 1rem;
            line-height: 1.5;
            font-size: clamp(0.8rem, 3vw, 1rem);
        }

        .progress-container {
            margin-top: 1rem;
        }

        .progress-bar {
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 5px;
            width: 0%;
            transition: width 1s ease;
        }

        .progress-text {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--light-text);
        }

        .back-button {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            margin-top: 2rem;
            border: none;
            cursor: pointer;
        }

        .back-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            background: var(--secondary);
        }

        /* Mobile Menu */
        @media (max-width: 768px) {
            header {
                padding: 0.4rem 0.8rem;
                height: 55px;
            }
            
            .main-content {
                padding-top: 65px;
                min-height: calc(100vh - 65px);
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.95);
                flex-direction: column;
                padding: 0.8rem;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                gap: 0.4rem;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 0.6rem;
                text-align: center;
                border-radius: 8px;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        /* Particle Animation */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: particle-float 10s infinite linear;
        }

        @keyframes particle-float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Additional Animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
    </style>
</head>
<body>
    <!-- Rocket Loading Screen -->
    <div class="rocket-loading" id="rocket-loading">
        <div class="rocket-container">
            <div class="rocket" id="rocket">
                🚀
                <div class="rocket-trail"></div>
                <div class="smoke"></div>
            </div>
            <div class="explosion" id="explosion">✨</div>
        </div>
        <div class="loading-text">Memuat Keajaiban...</div>
        <div class="click-instruction">Klik roket untuk meluncur!</div>
    </div>

    <!-- Header -->
    <header id="main-header">
        <div class="logo-text">
            <div class="logo">🌟</div>
            <h1>7 Kebiasaan Anak Indonesia Hebat</h1>
        </div>
        <nav class="nav-links" id="navLinks">
            <a href="#home">Beranda</a>
            <a href="#kebiasaan">Kebiasaan</a>
            <a href="#jurnal">Jurnal</a>
            <a href="#tentang">Tentang</a>
        </nav>
        <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Background Animation -->
        <div class="bg-animation">
            <div class="cloud"></div>
            <div class="cloud"></div>
            <div class="cloud"></div>
            <div class="cloud"></div>
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
        </div>

        <!-- Particle Animation -->
        <div class="particles"></div>

        <!-- Main Container -->
        <div class="container" id="main-container">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <div class="logo-container">
                    <div class="logo">🌟</div>
                </div>
                <h1 class="title">7 Kebiasaan<br>Anak Indonesia Hebat</h1>
                <p class="subtitle">Menjadi Anak Hebat dengan Kebiasaan Baik Setiap Hari!</p>
                <div class="badge">🎯 Indonesia Emas 2045</div>
            </div>

            <!-- Habits Grid -->
            <div class="habits-grid">
                <!-- Habit 1 -->
                <div class="habit-card">
                    <div class="habit-number">1</div>
                    <div class="habit-icon">🌅</div>
                    <h3 class="habit-title">Bangun Pagi</h3>
                    <p class="habit-description">Mulai hari dengan semangat dan disiplin!</p>
                </div>

                <!-- Habit 2 -->
                <div class="habit-card">
                    <div class="habit-number">2</div>
                    <div class="habit-icon">🤲</div>
                    <h3 class="habit-title">Beribadah</h3>
                    <p class="habit-description">Mendekatkan diri kepada Tuhan Yang Maha Esa</p>
                </div>

                <!-- Habit 3 -->
                <div class="habit-card">
                    <div class="habit-number">3</div>
                    <div class="habit-icon">⚽</div>
                    <h3 class="habit-title">Berolahraga</h3>
                    <p class="habit-description">Tubuh sehat, jiwa kuat, prestasi hebat!</p>
                </div>

                <!-- Habit 4 -->
                <div class="habit-card">
                    <div class="habit-number">4</div>
                    <div class="habit-icon">🥗</div>
                    <h3 class="habit-title">Makan Sehat</h3>
                    <p class="habit-description">Gizi seimbang untuk tumbuh cerdas</p>
                </div>

                <!-- Habit 5 -->
                <div class="habit-card">
                    <div class="habit-number">5</div>
                    <div class="habit-icon">📚</div>
                    <h3 class="habit-title">Gemar Belajar</h3>
                    <p class="habit-description">Buku adalah jendela dunia</p>
                </div>

                <!-- Habit 6 -->
                <div class="habit-card">
                    <div class="habit-number">6</div>
                    <div class="habit-icon">🤝</div>
                    <h3 class="habit-title">Bermasyarakat</h3>
                    <p class="habit-description">Bersama lebih baik, gotong royong!</p>
                </div>

                <!-- Habit 7 -->
                <div class="habit-card">
                    <div class="habit-number">7</div>
                    <div class="habit-icon">🌙</div>
                    <h3 class="habit-title">Tidur Cepat</h3>
                    <p class="habit-description">Istirahat cukup untuk esok yang cerah</p>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="cta-container">
                <button class="cta-button" id="start-journal">
                    <span>🚀 Mulai Petualangan!</span>
                </button>
            </div>
        </div>

        <!-- Journal Section - MODIFIED: Now placed inside main content -->
        <div class="journal-section" id="journal-section">
            <div class="journal-header">
                <h2 class="journal-title">Jurnal Kebiasaan Hebat</h2>
                <p class="journal-subtitle">Catat dan pantau perkembangan kebiasaan baikmu setiap hari!</p>
            </div>

            <div class="journal-grid">
                <!-- Journal Card 1 -->
                <div class="journal-card" data-habit="1">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">🌅</div>
                        <h3 class="journal-card-title">Bangun Pagi</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu bangun pagi hari ini? Catat jam bangun tidurmu!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 2 -->
                <div class="journal-card" data-habit="2">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">🤲</div>
                        <h3 class="journal-card-title">Beribadah</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu beribadah hari ini? Jangan lupa bersyukur!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 3 -->
                <div class="journal-card" data-habit="3">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">⚽</div>
                        <h3 class="journal-card-title">Berolahraga</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu berolahraga hari ini? Bergeraklah untuk kesehatan!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 4 -->
                <div class="journal-card" data-habit="4">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">🥗</div>
                        <h3 class="journal-card-title">Makan Sehat</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu makan makanan sehat hari ini? Perhatikan gizi!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 5 -->
                <div class="journal-card" data-habit="5">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">📚</div>
                        <h3 class="journal-card-title">Gemar Belajar</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu belajar hari ini? Baca buku atau pelajari hal baru!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 6 -->
                <div class="journal-card" data-habit="6">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">🤝</div>
                        <h3 class="journal-card-title">Bermasyarakat</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu berinteraksi dengan orang lain? Gotong royong yuk!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card 7 -->
                <div class="journal-card" data-habit="7">
                    <div class="journal-card-header">
                        <div class="journal-card-icon">🌙</div>
                        <h3 class="journal-card-title">Tidur Cepat</h3>
                    </div>
                    <p class="journal-card-description">Sudahkah kamu tidur tepat waktu? Istirahat yang cukup penting!</p>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" data-progress="0"></div>
                        </div>
                        <div class="progress-text">
                            <span>Progress</span>
                            <span class="progress-percent">0%</span>
                        </div>
                    </div>
                </div>
            </div>

            <button class="back-button" id="back-button">⬅ Kembali ke Atas</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>7 Kebiasaan Anak Indonesia Hebat</h3>
                <p>Website edukasi untuk membantu anak-anak Indonesia membangun kebiasaan baik sejak dini, menuju Indonesia Emas 2045.</p>
                <div class="social-icons">
                    <a href="#">📘</a>
                    <a href="#">📷</a>
                    <a href="#">🐦</a>
                    <a href="#">📺</a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Kebiasaan Hebat</h3>
                <ul class="footer-links">
                    <li><a href="#">Bangun Pagi</a></li>
                    <li><a href="#">Beribadah</a></li>
                    <li><a href="#">Berolahraga</a></li>
                    <li><a href="#">Makan Sehat</a></li>
                    <li><a href="#">Gemar Belajar</a></li>
                    <li><a href="#">Bermasyarakat</a></li>
                    <li><a href="#">Tidur Cepat</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p>📧 info@anakindonesiahebat.id</p>
                <p>📞 +62 123 4567 890</p>
                <p>📍 Jakarta, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 7 Kebiasaan Anak Indonesia Hebat. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <!-- Floating Mascot -->
    <div class="mascot">🦸</div>

    <script>
        // Rocket Loading Screen
        const rocketLoading = document.getElementById('rocket-loading');
        const rocket = document.getElementById('rocket');
        const explosion = document.getElementById('explosion');
        const mainContainer = document.getElementById('main-container');
        const header = document.getElementById('main-header');
        const footer = document.querySelector('footer');
        const journalSection = document.getElementById('journal-section');

        // Initially hide main content, header and footer
        mainContainer.style.display = 'none';
        header.style.display = 'none';
        footer.style.display = 'none';
        // Journal section tetap tersembunyi di awal
        journalSection.style.display = 'none';

        // Rocket launch functionality
        rocket.addEventListener('click', function() {
            // Add launch class to rocket
            this.classList.add('launch');
            
            // Show explosion at the end of rocket animation
            setTimeout(() => {
                explosion.classList.add('active');
                
                // Hide loading screen after explosion
                setTimeout(() => {
                    rocketLoading.style.opacity = '0';
                    setTimeout(() => {
                        rocketLoading.style.display = 'none';
                        // Show main content, header and footer
                        mainContainer.style.display = 'flex';
                        header.style.display = 'flex';
                        footer.style.display = 'block';
                    }, 1000);
                }, 1200);
            }, 3500);
        });

        // Fix untuk tombol "Mulai Petualangan" - MODIFIED: Show journal below
        const startJournalBtn = document.getElementById('start-journal');
        const backButton = document.getElementById('back-button');

        startJournalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tampilkan journal section
            journalSection.style.display = 'flex';
            
            // Scroll ke journal section dengan smooth animation
            journalSection.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
            
            // Animate progress bars
            setTimeout(() => {
                document.querySelectorAll('.progress-fill').forEach(bar => {
                    const randomProgress = Math.floor(Math.random() * 100);
                    bar.style.width = randomProgress + '%';
                    bar.parentElement.nextElementSibling.querySelector('.progress-percent').textContent = randomProgress + '%';
                });
            }, 500);
        });

        // Back button functionality - MODIFIED: Scroll back to top
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Scroll kembali ke bagian atas halaman
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            navLinks.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('header')) {
                navLinks.classList.remove('active');
            }
        });

        // Create stars randomly
        function createStars() {
            const bgAnimation = document.querySelector('.bg-animation');
            for (let i = 0; i < 40; i++) {
                const star = document.createElement('div');
                star.className = 'stars';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 2 + 's';
                bgAnimation.appendChild(star);
            }
        }

        // Create particles
        function createParticles() {
            const particlesContainer = document.querySelector('.particles');
            for (let i = 0; i < 25; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 10 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                particle.style.width = (Math.random() * 8 + 4) + 'px';
                particle.style.height = particle.style.width;
                particlesContainer.appendChild(particle);
            }
        }

        createStars();
        createParticles();

        // Add click sound effect (visual feedback)
        document.querySelectorAll('.habit-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });

        // Journal card interactions
        document.querySelectorAll('.journal-card').forEach(card => {
            card.addEventListener('click', function() {
                const habitNumber = this.getAttribute('data-habit');
                const habitNames = [
                    '', // index 0 empty
                    'Bangun Pagi',
                    'Beribadah',
                    'Berolahraga',
                    'Makan Sehat',
                    'Gemar Belajar',
                    'Bermasyarakat',
                    'Tidur Cepat'
                ];
                
                const currentProgress = parseInt(this.querySelector('.progress-percent').textContent);
                let newProgress = currentProgress + 10;
                
                if (newProgress > 100) {
                    newProgress = 0;
                }
                
                this.querySelector('.progress-fill').style.width = newProgress + '%';
                this.querySelector('.progress-percent').textContent = newProgress + '%';
                
                // Show confirmation
                const message = newProgress === 100 ? 
                    `🎉 Selamat! Kamu telah menyelesaikan kebiasaan "${habitNames[habitNumber]}" hari ini!` :
                    `👍 Kamu telah mencatat kemajuan dalam kebiasaan "${habitNames[habitNumber]}"!`;
                
                showMessage(message);
            });
        });

        // Add interactive mascot
        const mascot = document.querySelector('.mascot');
        mascot.addEventListener('click', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = '';
            }, 100);
            
            // Random motivational messages
            const messages = [
                '💪 Semangat Anak Hebat!',
                '🌟 Kamu Luar Biasa!',
                '🎯 Terus Berlatih Ya!',
                '🏆 Kamu Pasti Bisa!',
                '❤️ Aku Bangga Padamu!',
                '✨ Teruskan Kebiasaan Baikmu!',
                '🌈 Masa Depan Cerah Menantimu!'
            ];
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            
            showMessage(randomMessage);
        });

        // Show message function
        function showMessage(message) {
            // Create temporary message
            const msgDiv = document.createElement('div');
            msgDiv.textContent = message;
            msgDiv.style.cssText = `
                position: fixed;
                bottom: 8rem;
                right: 1.5rem;
                background: white;
                padding: 0.8rem 1.5rem;
                border-radius: 15px;
                font-weight: 700;
                font-size: 1rem;
                color: var(--primary);
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                animation: slideInRight 0.5s ease;
                z-index: 101;
                max-width: 250px;
                text-align: center;
            `;
            document.body.appendChild(msgDiv);
            
            setTimeout(() => {
                msgDiv.style.animation = 'slideOutRight 0.5s ease forwards';
                setTimeout(() => {
                    msgDiv.remove();
                }, 500);
            }, 3000);
        }

        // Add hover sound effect simulation
        document.querySelectorAll('.habit-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const icon = this.querySelector('.habit-icon');
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            });
            
            card.addEventListener('mouseleave', function() {
                const icon = this.querySelector('.habit-icon');
                icon.style.transform = '';
            });
        });
    </script>
</body>
</html>