<?php
// $activePage should be set before including this file.
// Values: 'home', 'how_we_work', 'why_it_works', 'guide', 'contact'
if (!isset($activePage)) $activePage = '';
$howItWorksPages = ['how_we_work', 'why_it_works', 'guide'];
$howItWorksActive = in_array($activePage, $howItWorksPages);
?>
<style>
    /* ===== SHARED NAVBAR STYLES ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .mq-navbar {
        width: 100%;
        background: #083d49;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 16px rgba(8,61,73,0.22);
        font-family: 'Inter', Arial, sans-serif;
    }

    .mq-nav-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 5%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 66px;
    }

    .mq-nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .mq-nav-logo-img {
        height: 40px;
        width: auto;
        border-radius: 8px;
        object-fit: contain;
        background: #eae6e3;
        padding: 3px;
    }

    .mq-nav-brand-text {
        font-size: 17px;
        font-weight: 700;
        color: #eae6e3;
        letter-spacing: 0.3px;
    }

    .mq-nav-brand-text em {
        font-style: normal;
        opacity: 0.7;
        font-weight: 500;
    }

    .mq-nav-links {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 4px;
        margin: 0;
        padding: 0;
    }

    .mq-nav-links > li {
        position: relative;
    }

    .mq-nav-links > li > a {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 16px;
        border-radius: 8px;
        color: rgba(234,230,227,0.82);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.18s, color 0.18s;
        cursor: pointer;
        white-space: nowrap;
    }

    .mq-nav-links > li > a:hover,
    .mq-nav-links > li > a.mq-active {
        background: rgba(255,255,255,0.12);
        color: #ffffff;
    }

    .mq-nav-links > li > a.mq-dropdown-active {
        background: rgba(255,255,255,0.12);
        color: #ffffff;
    }

    .mq-chevron {
        width: 11px;
        height: 11px;
        transition: transform 0.22s ease;
        flex-shrink: 0;
    }

    .mq-has-dropdown:hover .mq-chevron,
    .mq-has-dropdown.mq-open .mq-chevron {
        transform: rotate(180deg);
    }

    /* Dropdown */
    .mq-dropdown {
        display: block;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        background: #ffffff;
        border-radius: 12px;
        min-width: 200px;
        box-shadow: 0 12px 40px rgba(8,61,73,0.18), 0 2px 8px rgba(8,61,73,0.10);
        list-style: none;
        padding: 8px;
        margin: 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-6px);
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
        z-index: 200;
        pointer-events: none;
    }

    .mq-has-dropdown:hover .mq-dropdown,
    .mq-has-dropdown.mq-open .mq-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    .mq-dropdown li a {
        display: block;
        padding: 10px 14px;
        border-radius: 8px;
        color: #083d49;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: background 0.15s;
    }

    .mq-dropdown li a:hover {
        background: #eae6e3;
        color: #083d49;
    }

    .mq-dropdown li a.mq-active {
        background: #083d49;
        color: #ffffff;
    }

    .mq-dropdown-divider {
        height: 1px;
        background: rgba(8,61,73,0.1);
        margin: 6px 8px;
    }

    /* Contact Us button style */
    .mq-nav-contact {
        background: rgba(255,255,255,0.14) !important;
        border: 1px solid rgba(255,255,255,0.25) !important;
    }

    .mq-nav-contact:hover {
        background: rgba(255,255,255,0.22) !important;
    }

    .mq-nav-contact.mq-active {
        background: rgba(255,255,255,0.25) !important;
    }

    /* Hamburger for mobile */
    .mq-nav-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
    }

    .mq-nav-toggle span {
        display: block;
        width: 22px;
        height: 2px;
        background: #eae6e3;
        border-radius: 2px;
        transition: transform 0.22s, opacity 0.22s;
    }

    @media (max-width: 700px) {
        .mq-nav-toggle {
            display: flex;
        }

        .mq-nav-links {
            display: none;
            position: absolute;
            top: 66px;
            left: 0;
            right: 0;
            background: #083d49;
            flex-direction: column;
            align-items: stretch;
            gap: 0;
            padding: 8px 16px 16px;
            box-shadow: 0 8px 24px rgba(8,61,73,0.18);
        }

        .mq-nav-links.mq-open {
            display: flex;
        }

        .mq-nav-links > li > a {
            padding: 13px 8px;
            border-radius: 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .mq-dropdown {
            position: static;
            box-shadow: none;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            margin: 4px 0;
            opacity: 1;
            visibility: visible;
            transform: none;
            pointer-events: auto;
            display: none;
        }

        .mq-dropdown li a {
            color: rgba(234,230,227,0.85);
            font-size: 13px;
            padding: 10px 16px;
        }

        .mq-dropdown li a:hover,
        .mq-dropdown li a.mq-active {
            background: rgba(255,255,255,0.14);
            color: #fff;
        }

        .mq-has-dropdown.mq-open .mq-dropdown {
            display: block;
        }
    }
</style>

<nav class="mq-navbar" id="mqNavbar">
    <div class="mq-nav-inner">
        <a href="main.php" class="mq-nav-brand">
            <img src="assets/logo.jpg" alt="MediQueue SA" class="mq-nav-logo-img">
            <span class="mq-nav-brand-text">MediQueue <em>SA</em></span>
        </a>

        <button class="mq-nav-toggle" id="mqNavToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="mq-nav-links" id="mqNavLinks">
            <li>
                <a href="main.php" <?= $activePage === 'home' ? 'class="mq-active"' : '' ?>>
                    Home
                </a>
            </li>
            <li class="mq-has-dropdown" id="mqHowDropdown">
                <a href="#" class="mq-dropdown-trigger <?= $howItWorksActive ? 'mq-dropdown-active' : '' ?>"
                   onclick="return false;">
                    How It Works
                    <svg class="mq-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <ul class="mq-dropdown">
                    <li><a href="how_we_work.php" <?= $activePage === 'how_we_work' ? 'class="mq-active"' : '' ?>>How We Work</a></li>
                    <li class="mq-dropdown-divider" role="separator"></li>
                    <li><a href="why_it_works.php" <?= $activePage === 'why_it_works' ? 'class="mq-active"' : '' ?>>Why It Works</a></li>
                    <li class="mq-dropdown-divider" role="separator"></li>
                    <li><a href="guide.php" <?= $activePage === 'guide' ? 'class="mq-active"' : '' ?>>A Guide</a></li>
                </ul>
            </li>
            <li>
                <a href="contact.php" class="mq-nav-contact <?= $activePage === 'contact' ? 'mq-active' : '' ?>">
                    Contact Us
                </a>
            </li>
        </ul>
    </div>
</nav>

<script>
(function () {
    var toggle = document.getElementById('mqNavToggle');
    var navLinks = document.getElementById('mqNavLinks');
    var howDropdown = document.getElementById('mqHowDropdown');

    // Mobile hamburger
    toggle.addEventListener('click', function () {
        navLinks.classList.toggle('mq-open');
    });

    // Mobile dropdown toggle
    if (howDropdown) {
        var trigger = howDropdown.querySelector('.mq-dropdown-trigger');
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            howDropdown.classList.toggle('mq-open');
        });
    }

    // Close mobile nav on outside click
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#mqNavbar')) {
            navLinks.classList.remove('mq-open');
        }
    });
}());
</script>
