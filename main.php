<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA — Clinical Queuing &amp; Triage Platform</title>
    <meta name="description" content="Official digital queuing, clinical triage, and wait-time management platform for South African hospitals and healthcare facilities.">
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        
        :root {
            --mq-canvas: #eae6e3;
            --mq-canvas-soft: #f2efec;
            --mq-surface: #ffffff;
            --mq-surface-alt: #f7f5f2;

            --mq-primary: #083d49;
            --mq-primary-hover: #052932;
            --mq-primary-light: rgba(8, 61, 73, 0.08);
            --mq-primary-border: rgba(8, 61, 73, 0.16);

            --mq-sage: #597948;
            --mq-sage-hover: #476239;
            --mq-sage-light: rgba(89, 121, 72, 0.12);
            --mq-sage-border: rgba(89, 121, 72, 0.28);

            --mq-text-main: #083d49;
            --mq-text-body: #2b4146;
            --mq-text-muted: #5e747a;
            --mq-text-subtle: #7f959b;

            --mq-border-subtle: rgba(8, 61, 73, 0.12);
            --mq-border-strong: rgba(8, 61, 73, 0.22);

            --mq-urgency-high-bg: #fde8e8;
            --mq-urgency-high-text: #9b1c1c;
            --mq-urgency-high-border: #f8b4b4;

            --mq-urgency-mod-bg: #fef9c3;
            --mq-urgency-mod-text: #854d0e;
            --mq-urgency-mod-border: #fde047;

            --mq-urgency-low-bg: #eaf3e8;
            --mq-urgency-low-text: #27521e;
            --mq-urgency-low-border: #b8dcab;

            --mq-shadow-xs: 0 1px 2px rgba(8, 61, 73, 0.04);
            --mq-shadow-sm: 0 2px 8px rgba(8, 61, 73, 0.05), 0 1px 2px rgba(8, 61, 73, 0.03);
            --mq-shadow-md: 0 8px 24px rgba(8, 61, 73, 0.07), 0 2px 6px rgba(8, 61, 73, 0.03);
            --mq-shadow-lg: 0 16px 40px rgba(8, 61, 73, 0.10), 0 4px 12px rgba(8, 61, 73, 0.04);

            --mq-radius-sm: 6px;
            --mq-radius-md: 10px;
            --mq-radius-lg: 16px;
            --mq-radius-full: 9999px;

            --mq-font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --mq-font-mono: 'JetBrains Mono', monospace;
            --mq-transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 16px;
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            font-family: var(--mq-font-sans);
            background-color: var(--mq-canvas);
            color: var(--mq-text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, textarea, select {
            font-family: inherit;
            font-size: inherit;
        }

        
        .icon {
            display: inline-block;
            width: 18px;
            height: 18px;
            stroke-width: 2;
            stroke: currentColor;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            vertical-align: middle;
            flex-shrink: 0;
        }

        
        .top-system-strip {
            background-color: var(--mq-primary);
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            font-weight: 500;
            padding: 6px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .system-status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot-pulse {
            width: 7px;
            height: 7px;
            background-color: #4ade80;
            border-radius: var(--mq-radius-full);
            box-shadow: 0 0 8px rgba(74, 222, 128, 0.7);
            animation: softPulse 2.5s infinite ease-in-out;
        }

        @keyframes softPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.45; transform: scale(0.85); }
        }

        .top-bar-emergency {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.7);
        }

        .top-bar-emergency strong {
            color: #ffffff;
        }

        
        .site-header {
            background-color: var(--mq-surface);
            border-bottom: 1px solid var(--mq-border-subtle);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--mq-shadow-xs);
        }

        .header-content-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        
        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            text-decoration: none;
            user-select: none;
        }

        .official-logo-graphic {
            height: 48px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            display: block;
        }

        .brand-text-subtitle {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--mq-sage);
            display: block;
            border-left: 1px solid var(--mq-border-subtle);
            padding-left: 12px;
            line-height: 1.2;
        }

        .brand-text-subtitle span {
            display: block;
            font-weight: 500;
            color: var(--mq-text-muted);
            letter-spacing: 0.02em;
        }

        
        .nav-links-cluster {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
        }

        .nav-item-btn {
            background: transparent;
            border: none;
            color: var(--mq-text-body);
            font-size: 14px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: var(--mq-radius-sm);
            cursor: pointer;
            transition: var(--mq-transition);
        }

        .nav-item-btn:hover,
        .nav-item-btn.active {
            color: var(--mq-primary);
            background-color: var(--mq-primary-light);
        }

        
        .header-actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-header-patient {
            background-color: var(--mq-primary);
            color: #ffffff;
            border: 1px solid transparent;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: var(--mq-radius-md);
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-header-patient:hover {
            background-color: var(--mq-primary-hover);
            box-shadow: 0 4px 12px rgba(8, 61, 73, 0.25);
        }

        .btn-header-doctor {
            background-color: var(--mq-surface-alt);
            color: var(--mq-primary);
            border: 1px solid var(--mq-border-strong);
            padding: 9px 16px;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: var(--mq-radius-md);
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-header-doctor:hover {
            background-color: #ffffff;
            border-color: var(--mq-primary);
        }

        
        .app-view-wrapper {
            max-width: 1240px;
            width: 100%;
            margin: 0 auto;
            padding: 32px 24px 64px;
            flex-grow: 1;
        }

        .view-frame {
            display: none;
            animation: frameIn 0.22s ease-out forwards;
        }

        .view-frame.active {
            display: block;
        }

        @keyframes frameIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .frame-nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--mq-border-subtle);
        }

        .btn-return-home {
            background: transparent;
            border: none;
            color: var(--mq-text-muted);
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: var(--mq-radius-sm);
            transition: var(--mq-transition);
        }

        .btn-return-home:hover {
            color: var(--mq-primary);
            background-color: var(--mq-primary-light);
        }

        .context-chip {
            font-size: 12px;
            font-weight: 600;
            color: var(--mq-sage);
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            padding: 4px 12px;
            border-radius: var(--mq-radius-full);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        
        .hero-editorial-card {
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            padding: 48px;
            margin-bottom: 36px;
            box-shadow: var(--mq-shadow-sm);
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .hero-left-col {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .category-eyebrow {
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--mq-sage);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .hero-main-title {
            font-size: clamp(32px, 3.8vw, 46px);
            font-weight: 800;
            line-height: 1.15;
            color: var(--mq-primary);
            letter-spacing: -0.025em;
        }

        .hero-supporting-text {
            font-size: 16.5px;
            line-height: 1.65;
            color: var(--mq-text-body);
        }

        .hero-actions-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .btn-action-primary {
            background-color: var(--mq-primary);
            color: #ffffff;
            border: 1px solid transparent;
            padding: 14px 24px;
            border-radius: var(--mq-radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(8, 61, 73, 0.2);
        }

        .btn-action-primary:hover {
            background-color: var(--mq-primary-hover);
            transform: translateY(-1px);
        }

        .btn-action-secondary {
            background-color: var(--mq-surface);
            color: var(--mq-sage);
            border: 1.5px solid var(--mq-sage);
            padding: 13px 22px;
            border-radius: var(--mq-radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action-secondary:hover {
            background-color: var(--mq-sage-light);
        }

        .trust-assurances-list {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            font-size: 12.5px;
            color: var(--mq-text-muted);
            font-weight: 500;
            flex-wrap: wrap;
        }

        .trust-assurance-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        
        .live-preview-widget {
            background: var(--mq-surface-alt);
            border: 1px solid var(--mq-border-strong);
            border-radius: var(--mq-radius-md);
            padding: 28px;
            box-shadow: var(--mq-shadow-xs);
        }

        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--mq-border-subtle);
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .widget-facility-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--mq-primary);
        }

        .widget-facility-sub {
            font-size: 11.5px;
            color: var(--mq-text-muted);
        }

        .widget-active-pill {
            background: var(--mq-sage-light);
            border: 1px solid var(--mq-sage-border);
            color: var(--mq-sage);
            padding: 4px 10px;
            border-radius: var(--mq-radius-full);
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .widget-metrics-duo {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .widget-metric-card {
            background: #ffffff;
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-sm);
            padding: 16px;
        }

        .widget-metric-card .val {
            font-family: var(--mq-font-mono);
            font-size: 26px;
            font-weight: 800;
            color: var(--mq-primary);
            line-height: 1.1;
        }

        .widget-metric-card .lbl {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--mq-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 4px;
        }

        .widget-triage-preview {
            background: #ffffff;
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-sm);
            padding: 14px 16px;
            font-size: 13px;
            color: var(--mq-text-body);
            line-height: 1.5;
        }

        .widget-triage-preview strong {
            color: var(--mq-primary);
        }

        
        .portal-entry-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            margin-bottom: 40px;
        }

        .portal-entry-card {
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            padding: 38px 34px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--mq-shadow-sm);
            transition: var(--mq-transition);
        }

        .portal-entry-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--mq-shadow-md);
            border-color: var(--mq-primary);
        }

        .entry-card-badge {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--mq-sage);
            margin-bottom: 8px;
        }

        .portal-entry-card h3 {
            font-size: 22px;
            font-weight: 800;
            color: var(--mq-primary);
            margin-bottom: 10px;
        }

        .portal-entry-card p {
            font-size: 14.5px;
            color: var(--mq-text-body);
            line-height: 1.6;
            margin-bottom: 24px;
            flex-grow: 1;
        }

        .portal-features-list {
            list-style: none;
            margin-bottom: 24px;
            border-top: 1px solid var(--mq-border-subtle);
            padding-top: 16px;
        }

        .portal-features-list li {
            font-size: 13.5px;
            color: var(--mq-text-muted);
            padding: 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portal-features-list li .bullet {
            color: var(--mq-sage);
            font-weight: bold;
        }

        
        .mandate-wrapper {
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            padding: 48px;
            margin-bottom: 40px;
            box-shadow: var(--mq-shadow-sm);
        }

        .section-eyebrow-tag {
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--mq-sage);
            background: var(--mq-sage-light);
            padding: 4px 10px;
            border-radius: var(--mq-radius-full);
            display: inline-block;
            margin-bottom: 12px;
        }

        .section-headline {
            font-size: 28px;
            font-weight: 800;
            color: var(--mq-primary);
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .mandate-pillars-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 36px;
            margin-top: 32px;
        }

        .mandate-item h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--mq-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mandate-item p {
            font-size: 14px;
            color: var(--mq-text-body);
            line-height: 1.65;
        }

        
        .bottom-cta-banner {
            background: var(--mq-primary);
            color: #ffffff;
            border-radius: var(--mq-radius-lg);
            padding: 44px;
            text-align: center;
            box-shadow: var(--mq-shadow-md);
        }

        .bottom-cta-banner h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .bottom-cta-banner p {
            font-size: 15.5px;
            color: rgba(255, 255, 255, 0.85);
            max-width: 640px;
            margin: 0 auto 24px;
        }

        .btn-cta-patient {
            background-color: #ffffff;
            color: var(--mq-primary);
            border: 1.5px solid #ffffff;
            padding: 13px 26px;
            border-radius: var(--mq-radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        .btn-cta-patient:hover {
            background-color: var(--mq-canvas);
            border-color: var(--mq-canvas);
            color: var(--mq-primary);
            transform: translateY(-1px);
        }

        .btn-cta-staff {
            background-color: rgba(255, 255, 255, 0.10);
            color: #ffffff;
            border: 1.5px solid rgba(255, 255, 255, 0.65);
            padding: 13px 24px;
            border-radius: var(--mq-radius-md);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--mq-transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cta-staff:hover {
            background-color: rgba(255, 255, 255, 0.22);
            border-color: #ffffff;
            color: #ffffff;
            transform: translateY(-1px);
        }

        
        .form-panel-card {
            max-width: 580px;
            margin: 0 auto;
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            padding: 40px 36px;
            box-shadow: var(--mq-shadow-md);
        }

        .form-heading-block {
            margin-bottom: 24px;
            border-bottom: 1px solid var(--mq-border-subtle);
            padding-bottom: 16px;
        }

        .form-heading-block h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--mq-primary);
            margin-bottom: 4px;
        }

        .form-heading-block p {
            font-size: 13.5px;
            color: var(--mq-text-muted);
        }

        .input-row-group {
            margin-bottom: 18px;
        }

        .input-label-text {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--mq-primary);
            margin-bottom: 6px;
        }

        .text-input-field {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--mq-border-strong);
            border-radius: var(--mq-radius-sm);
            background: #ffffff;
            color: var(--mq-text-main);
            font-size: 14.5px;
            transition: var(--mq-transition);
        }

        .text-input-field:focus {
            outline: none;
            border-color: var(--mq-primary);
            box-shadow: 0 0 0 3px var(--mq-primary-light);
        }

        textarea.text-input-field {
            min-height: 100px;
            resize: vertical;
        }

        
        .ticket-slip-card {
            max-width: 620px;
            margin: 0 auto;
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            overflow: hidden;
            box-shadow: var(--mq-shadow-lg);
        }

        .ticket-slip-head {
            background: var(--mq-primary);
            color: #ffffff;
            padding: 22px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-slip-body {
            padding: 32px 28px;
            text-align: center;
        }

        .ticket-number-display {
            display: inline-block;
            background: var(--mq-surface-alt);
            border: 1.5px dashed var(--mq-border-strong);
            border-radius: var(--mq-radius-md);
            padding: 14px 32px;
            margin-bottom: 24px;
        }

        .ticket-number-display .code {
            font-family: var(--mq-font-mono);
            font-size: 36px;
            font-weight: 800;
            color: var(--mq-primary);
        }

        .ticket-trio-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 24px;
            text-align: left;
        }

        .trio-metric-cell {
            background: var(--mq-surface-alt);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-sm);
            padding: 14px;
        }

        .trio-metric-cell .val {
            font-size: 20px;
            font-weight: 800;
            color: var(--mq-primary);
            margin-top: 2px;
        }

        
        .doctor-console-grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 24px;
            align-items: start;
        }

        .doctor-card-panel {
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-lg);
            padding: 24px;
            box-shadow: var(--mq-shadow-sm);
        }

        .btn-call-patient {
            width: 100%;
            padding: 15px;
            border-radius: var(--mq-radius-md);
            border: none;
            background-color: var(--mq-sage);
            color: #ffffff;
            font-size: 15.5px;
            font-weight: 800;
            cursor: pointer;
            transition: var(--mq-transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(89, 121, 72, 0.25);
            margin-bottom: 18px;
        }

        .btn-call-patient:hover {
            background-color: var(--mq-sage-hover);
        }

        .active-room-slot {
            background: var(--mq-surface-alt);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-md);
            padding: 18px;
        }

        .queue-data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }

        .queue-data-table th {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--mq-text-muted);
            border-bottom: 2px solid var(--mq-border-subtle);
            background: var(--mq-surface-alt);
        }

        .queue-data-table td {
            padding: 12px;
            border-bottom: 1px solid var(--mq-border-subtle);
            color: var(--mq-text-body);
        }

        
        .urgency-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--mq-radius-full);
            display: inline-block;
        }

        .urgency-pill.high {
            background: var(--mq-urgency-high-bg);
            color: var(--mq-urgency-high-text);
            border: 1px solid var(--mq-urgency-high-border);
        }

        .urgency-pill.moderate {
            background: var(--mq-urgency-mod-bg);
            color: var(--mq-urgency-mod-text);
            border: 1px solid var(--mq-urgency-mod-border);
        }

        .urgency-pill.low {
            background: var(--mq-urgency-low-bg);
            color: var(--mq-urgency-low-text);
            border: 1px solid var(--mq-urgency-low-border);
        }

        
        .guide-step-row {
            background: var(--mq-surface);
            border: 1px solid var(--mq-border-subtle);
            border-radius: var(--mq-radius-md);
            padding: 22px;
            margin-bottom: 16px;
            display: flex;
            gap: 18px;
            align-items: flex-start;
        }

        .guide-step-num {
            width: 34px;
            height: 34px;
            border-radius: var(--mq-radius-full);
            background: var(--mq-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        
        .site-footer {
            background: var(--mq-surface);
            border-top: 1px solid var(--mq-border-subtle);
            padding: 36px 24px;
            margin-top: auto;
            font-size: 13px;
            color: var(--mq-text-muted);
        }

        .footer-inner-flex {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        .footer-links-row {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .footer-links-row a:hover {
            color: var(--mq-primary);
            text-decoration: underline;
        }

        
        @media (max-width: 960px) {
            .hero-editorial-card,
            .portal-entry-grid,
            .mandate-pillars-grid,
            .doctor-console-grid {
                grid-template-columns: 1fr;
            }

            .header-content-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links-cluster {
                display: none;
            }

            .ticket-trio-metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    
    <div class="top-system-strip">
        <div class="system-status-indicator">
            <span class="status-dot-pulse"></span>
            <span>SYSTEM ACTIVE &bull; PROVINCIAL HEALTHCARE GATEWAY (SOUTH AFRICA)</span>
        </div>
        <div class="top-bar-emergency">
            Emergency Dispatch: <strong>112 (Mobile)</strong> &bull; <strong>10177 (Ambulance)</strong>
        </div>
    </div>

    
    <header class="site-header">
        <div class="header-content-inner">
            
            
            <a href="#" class="brand-logo-container" onclick="showView('home'); return false;">
                <img src="assets/logo-transparent.png" 
                     alt="MediQueue SA Official Logo" 
                     class="official-logo-graphic"
                     onerror="this.onerror=null; this.src='assets/logo.png';">
                <div class="brand-text-subtitle">
                    Republic of South Africa
                    <span>Virtual Healthcare Triage</span>
                </div>
            </a>

            
            <nav>
                <ul class="nav-links-cluster">
                    <li><button class="nav-item-btn" onclick="showView('how-to-use')">How It Works</button></li>
                    <li><button class="nav-item-btn" onclick="showView('solutions')">Clinical Solutions</button></li>
                    <li><button class="nav-item-btn" onclick="showView('support')">Facility Support</button></li>
                    <li><button class="nav-item-btn" onclick="showView('about')">About Us</button></li>
                </ul>
            </nav>

            
            <div class="header-actions-group">
                <button class="btn-header-patient" onclick="showView('patient-portal')">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Patient Check-In</span>
                </button>
                <button class="btn-header-doctor" onclick="showView('doctor-portal')">
                    <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>Staff Console</span>
                </button>
            </div>

        </div>
    </header>

    
    <main class="app-view-wrapper">

        
        <section id="view-home" class="view-frame active">
            
            
            <section class="hero-editorial-card">
                <div class="hero-left-col">
                    <div class="category-eyebrow">
                        <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        <span>CLINICAL QUEUE DE-CONGESTION PROTOCOL</span>
                    </div>

                    <h1 class="hero-main-title">
                        Dignified, contactless waiting for South African healthcare.
                    </h1>

                    <p class="hero-supporting-text">
                        MediQueue SA connects patients with hospital outpatient clinics through dynamic triage sequencing. Register digitally, receive objective urgency scoring, and wait safely until your consultation is imminent.
                    </p>

                    <div class="hero-actions-row">
                        <button class="btn-action-primary" onclick="showView('patient-portal')">
                            <span>Join Virtual Queue</span>
                            <svg class="icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                        <button class="btn-action-secondary" onclick="showView('doctor-portal')">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            <span>Practitioner Station</span>
                        </button>
                    </div>

                    <div class="trust-assurances-list">
                        <div class="trust-assurance-item">
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>POPIA Health Protected</span>
                        </div>
                        <div class="trust-assurance-item">
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>DoH Triage Protocol</span>
                        </div>
                        <div class="trust-assurance-item">
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>100% Free for Outpatients</span>
                        </div>
                    </div>
                </div>

                
                <div class="live-preview-widget">
                    <div class="widget-header">
                        <div>
                            <div class="widget-facility-title">Facility #1 &bull; Groote Schuur Regional OPD</div>
                            <div class="widget-facility-sub">General Outpatient Unit &bull; Live Telemetry</div>
                        </div>
                        <div class="widget-active-pill">
                            <span class="status-dot-pulse"></span>
                            <span>LIVE SYNC</span>
                        </div>
                    </div>

                    <div class="widget-metrics-duo">
                        <div class="widget-metric-card">
                            <div class="val">~14m</div>
                            <div class="lbl">Predicted Wait</div>
                        </div>
                        <div class="widget-metric-card">
                            <div class="val">3</div>
                            <div class="lbl">Patients Ahead</div>
                        </div>
                    </div>

                    <div class="widget-triage-preview">
                        <strong>Current Station Acuity:</strong> 2 Medical Officers active. High-acuity symptoms are dynamically prioritized ahead of non-urgent follow-ups.
                    </div>
                </div>
            </section>

            
            <section class="portal-entry-grid">
                
                
                <div class="portal-entry-card">
                    <div>
                        <div class="entry-card-badge">Outpatient Services</div>
                        <h3>Patient Queue Intake</h3>
                        <p>
                            Submit your symptoms, receive automated urgency classification, and secure a verified digital ticket without standing in physical lines.
                        </p>
                        <ul class="portal-features-list">
                            <li><span class="bullet">&bull;</span> Fast digital check-in from mobile or hospital kiosk</li>
                            <li><span class="bullet">&bull;</span> Real-time queue countdown and wait prediction</li>
                            <li><span class="bullet">&bull;</span> Wait safely off-site or in open courtyards</li>
                        </ul>
                    </div>
                    <button class="btn-action-primary" onclick="showView('patient-portal')" style="width: 100%; justify-content: center;">
                        <span>Enter Patient Portal &rarr;</span>
                    </button>
                </div>

                
                <div class="portal-entry-card">
                    <div>
                        <div class="entry-card-badge">Medical Personnel</div>
                        <h3>Clinical Staff Console</h3>
                        <p>
                            Institutional portal for physicians and triage nurses to oversee prioritized patient queues, review symptoms, and call the next case.
                        </p>
                        <ul class="portal-features-list">
                            <li><span class="bullet">&bull;</span> Automated priority sorting by symptom severity</li>
                            <li><span class="bullet">&bull;</span> One-click patient calling to designated room</li>
                            <li><span class="bullet">&bull;</span> Clinical consultation notes and referral tracking</li>
                        </ul>
                    </div>
                    <button class="btn-action-secondary" onclick="showView('doctor-portal')" style="width: 100%; justify-content: center;">
                        <span>Access Staff Console &rarr;</span>
                    </button>
                </div>

            </section>

            
            <section class="mandate-wrapper">
                <span class="section-eyebrow-tag">Public Health Rationale</span>
                <h2 class="section-headline">Why We Do What We Do</h2>
                <p style="font-size: 15.5px; color: var(--text-body); max-width: 820px; line-height: 1.7;">
                    South African public hospitals and regional clinics manage massive outpatient caseloads. Patients frequently wait between 4 and 8 hours in poorly ventilated indoor halls. MediQueue SA addresses these systemic problems with targeted healthcare technology.
                </p>

                <div class="mandate-pillars-grid">
                    <div class="mandate-item">
                        <h4>
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Airborne Contagion Mitigation</span>
                        </h4>
                        <p>
                            Dense waiting rooms facilitate the transmission of airborne pathogens, notably tuberculosis (TB) and seasonal respiratory viruses. MediQueue SA empowers patients to wait outdoors in well-ventilated areas or return only when consultation is imminent.
                        </p>
                    </div>

                    <div class="mandate-item">
                        <h4>
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <span>Restoring Patient Dignity</span>
                        </h4>
                        <p>
                            Uncertain waiting times cause severe emotional distress for vulnerable patients and caregivers. Providing a verified ticket number, live queue rank, and dynamic countdown replaces anxiety with transparency.
                        </p>
                    </div>

                    <div class="mandate-item">
                        <h4>
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            <span>Preventing Overlooked Deterioration</span>
                        </h4>
                        <p>
                            In manual chronological queues, acute conditions (such as pediatric dehydration, acute respiratory distress, or cardiac symptoms) can be hidden behind routine visits. Automated NLP triage escalates urgent presentations immediately.
                        </p>
                    </div>

                    <div class="mandate-item">
                        <h4>
                            <svg class="icon" viewBox="0 0 24 24" style="color:var(--mq-sage);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <span>Protecting Practitioner Capacity</span>
                        </h4>
                        <p>
                            Nurses and doctors in outpatient departments face crowd fatigue and repetitive administrative inquiries. MediQueue SA organizes incoming volume, provides pre-consultation symptom summaries, and protects clinical focus.
                        </p>
                    </div>
                </div>
            </section>

            
            <section class="bottom-cta-banner">
                <h2>Ready to Access the System?</h2>
                <p>
                    Select the Patient Check-In for outpatient registration, or authenticate with your facility badge for the Clinical Staff Console.
                </p>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <button class="btn-cta-patient" onclick="showView('patient-portal')">
                        <span>Patient Check-In &rarr;</span>
                    </button>
                    <button class="btn-cta-staff" onclick="showView('doctor-portal')">
                        <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Staff Console &rarr;</span>
                    </button>
                </div>
            </section>

        </section>

        
        <section id="view-patient-portal" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">Outpatient Check-in</span>
            </div>

            <div class="form-panel-card">
                <div class="form-heading-block">
                    <h2>Patient Clinical Intake</h2>
                    <p>Enter your patient details and chief complaints for immediate virtual queuing.</p>
                </div>

                <form onsubmit="handlePatientCheckIn(event)" id="patientCheckInForm">
                    <div id="patientFormAlert" style="display: none; padding: 12px 14px; border-radius: var(--mq-radius-sm); font-size: 13px; margin-bottom: 16px;"></div>

                    <div class="input-row-group">
                        <label class="input-label-text" for="formPatientId">Patient ID / National ID / Passport Number</label>
                        <input type="text" id="formPatientId" name="patient_id" class="text-input-field" placeholder="e.g. 9801125089087 or P-105" required autofocus>
                    </div>

                    <div class="input-row-group">
                        <label class="input-label-text" for="formHospitalId">Hospital / Healthcare Facility</label>
                        <select id="formHospitalId" name="hospital_id" class="text-input-field" required>
                            <option value="1">Loading healthcare facilities...</option>
                        </select>
                    </div>

                    <div class="input-row-group">
                        <label class="input-label-text" for="formSymptoms">Primary Symptoms &amp; Chief Complaint</label>
                        <textarea id="formSymptoms" name="symptoms" class="text-input-field" placeholder="Describe how you are feeling in natural language (e.g. persistent high fever for 3 days with chest tightness and severe fatigue)..." required></textarea>
                    </div>

                    <div style="background: var(--mq-surface-alt); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-sm); padding: 12px; margin-bottom: 20px; font-size: 12px; color: var(--mq-text-muted);">
                        <strong>Medical Notice:</strong> If you are experiencing sudden severe chest pain, major trauma, or difficulty speaking, alert the facility nurse immediately or dial 112.
                    </div>

                    <button type="submit" id="btnPatientSubmit" class="btn-action-primary" style="width: 100%; justify-content: center;">
                        <span>Submit Symptoms &amp; Generate Ticket</span>
                        <svg class="icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>

                    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--mq-border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                        <a href="login.php" style="color: var(--mq-teal); text-decoration: none; font-weight: 600;">Registered Patient Login &rarr;</a>
                        <a href="register.php" style="color: var(--mq-text-muted); text-decoration: none;">Create Patient Account</a>
                    </div>
                </form>
            </div>
        </section>

        
        <section id="view-patient-ticket" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Return to Home</span>
                </button>
                <div class="context-chip">
                    <span class="status-dot-pulse"></span>
                    <span>Ticket Synchronized</span>
                </div>
            </div>

            <div class="ticket-slip-card">
                <div class="ticket-slip-head">
                    <div>
                        <div style="font-size: 11.5px; opacity: 0.8; letter-spacing: 0.05em; text-transform: uppercase;" id="ticketFacilityMeta">FACILITY #1 &bull; REGIONAL OPD CLINIC</div>
                        <div style="font-size: 18px; font-weight: 800;">Virtual Queue Ticket</div>
                    </div>
                    <div id="displayTicketStatusBadge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #ffffff; padding: 4px 10px; border-radius: var(--mq-radius-full); font-size: 11px; font-weight: 700;">
                        ACTIVE IN QUEUE
                    </div>
                </div>

                <div class="ticket-slip-body">
                    <div class="ticket-number-display">
                        <div style="font-size: 11.5px; font-weight: 700; color: var(--mq-text-muted); text-transform: uppercase;">Ticket Number</div>
                        <div class="code" id="displayTicketCode">MQ-105</div>
                    </div>

                    <div class="ticket-trio-metrics">
                        <div class="trio-metric-cell">
                            <div style="font-size: 11px; font-weight: 600; color: var(--mq-text-muted); text-transform: uppercase;">Queue Rank</div>
                            <div class="val" id="displayTicketRank">#1</div>
                            <div id="displayTicketAhead" style="font-size: 11px; color: var(--mq-text-subtle); margin-top: 2px;">0 patients ahead</div>
                        </div>

                        <div class="trio-metric-cell">
                            <div style="font-size: 11px; font-weight: 600; color: var(--mq-text-muted); text-transform: uppercase;">Est. Wait</div>
                            <div class="val" id="displayTicketWait">~0 min</div>
                            <div style="font-size: 11px; color: var(--mq-text-subtle); margin-top: 2px;">Dynamic ML forecast</div>
                        </div>

                        <div class="trio-metric-cell">
                            <div style="font-size: 11px; font-weight: 600; color: var(--mq-text-muted); text-transform: uppercase;">Triage Level</div>
                            <div class="val" id="displayTicketAcuity" style="font-size: 16px; color: var(--mq-sage);">Moderate</div>
                            <div id="displayTicketScore" style="font-size: 11px; color: var(--mq-text-subtle); margin-top: 2px;">Priority Score: 50</div>
                        </div>
                    </div>

                    <div style="background: var(--mq-surface-alt); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-sm); padding: 14px 18px; text-align: left; font-size: 13.5px; color: var(--mq-text-body); margin-bottom: 24px;">
                        <strong>Outpatient Guidance:</strong> You may wait in the open shaded areas or courtyards. Please return to the acute consulting desk when your position reaches #1.
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: center;">
                        <button class="btn-action-primary" style="padding: 10px 20px; font-size: 13.5px;" onclick="simulateTicketRefresh()">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                            <span>Refresh Live Ticket</span>
                        </button>
                        <button class="btn-action-secondary" style="padding: 10px 20px; font-size: 13.5px;" onclick="showView('patient-portal')">
                            <span>Register Another Patient</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        
        <section id="view-doctor-portal" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">Staff Authentication</span>
            </div>

            <div class="form-panel-card">
                <div class="form-heading-block">
                    <h2>Clinical Practitioner Sign-In</h2>
                    <p>Enter your institutional staff identifier to open the prioritized outpatient queue.</p>
                </div>

                <form onsubmit="handleDoctorLogin(event)" id="doctorLoginForm">
                    <div id="doctorFormAlert" style="display: none; padding: 12px 14px; border-radius: var(--mq-radius-sm); font-size: 13px; margin-bottom: 16px;"></div>

                    <div class="input-row-group">
                        <label class="input-label-text" for="formDoctorHospital">Hospital / Healthcare Facility</label>
                        <select id="formDoctorHospital" class="text-input-field" required onchange="loadDoctorListForHospital(this.value)">
                            <option value="1">Addington Hospital (Durban)</option>
                        </select>
                    </div>

                    <div class="input-row-group">
                        <label class="input-label-text" for="formDoctorId">Doctor / Medical Officer</label>
                        <select id="formDoctorId" class="text-input-field" required>
                            <option value="1">Dr. Thabo Mokoena (General Medicine)</option>
                        </select>
                    </div>

                    <button type="submit" id="btnDoctorSubmit" class="btn-action-primary" style="width: 100%; justify-content: center;">
                        <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Access Clinical Queue Dashboard</span>
                    </button>

                    <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--mq-border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                        <a href="doctor.php" style="color: var(--mq-teal); text-decoration: none; font-weight: 600;">Full Doctor Workspace (Rx &amp; Referrals) &rarr;</a>
                        <a href="admin.php" style="color: var(--mq-text-muted); text-decoration: none;">Admin Analytics &rarr;</a>
                    </div>
                </form>
            </div>
        </section>

        
        <section id="view-doctor-dashboard" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Sign Out to Home</span>
                </button>
                <span class="context-chip" id="doctorSessionIndicator">Authenticated: Dr. #1 (Facility 1)</span>
            </div>

            <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h2 style="font-size: 19px; font-weight: 800; color: var(--mq-primary);" id="dashboardFacilityTitle">Facility #1 Clinical Queue Console</h2>
                    <span style="font-size: 13px; color: var(--mq-text-muted);">Real-time patient intake &amp; automated triage prioritization</span>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div style="text-align: right;">
                        <div style="font-size: 18px; font-weight: 800; color: var(--mq-primary);" id="statWaitingQueue">3</div>
                        <div style="font-size: 11px; color: var(--mq-text-muted); text-transform: uppercase;">In Queue</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 18px; font-weight: 800; color: var(--mq-sage);" id="statCompletedQueue">14</div>
                        <div style="font-size: 11px; color: var(--mq-text-muted); text-transform: uppercase;">Completed</div>
                    </div>
                </div>
            </div>

            <div class="doctor-console-grid">
                
                
                <div class="doctor-card-panel">
                    <span class="category-eyebrow" style="color:var(--mq-sage); margin-bottom: 8px;">Active Station</span>
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--mq-primary); margin-bottom: 16px;">Consultation Room</h3>

                    <button class="btn-call-patient" onclick="doctorTakeNextPatient()">
                        <svg class="icon" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        <span>Take Next Patient</span>
                    </button>

                    <div class="active-room-slot" id="doctorActiveRoomSlot">
                        <div style="text-align: center; padding: 24px 10px; color: var(--mq-text-muted);">
                            <div style="font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Room Currently Idle</div>
                            <div style="font-size: 13px;">Click "Take Next Patient" above to call the next patient.</div>
                        </div>
                    </div>
                </div>

                
                <div class="doctor-card-panel">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <div>
                            <h3 style="font-size: 18px; font-weight: 800; color: var(--mq-primary);">Prioritized Outpatient List</h3>
                            <p style="font-size: 12px; color: var(--mq-text-muted);">Sorted automatically by clinical urgency &amp; waiting duration</p>
                        </div>
                        <button class="nav-item-btn" style="background: var(--mq-surface-alt);" onclick="refreshDoctorTable()">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                            <span>Sync</span>
                        </button>
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="queue-data-table">
                            <thead>
                                <tr>
                                    <th>Ticket</th>
                                    <th>Patient ID</th>
                                    <th>Triage Tier</th>
                                    <th>Est. Wait</th>
                                    <th>Reported Symptoms</th>
                                </tr>
                            </thead>
                            <tbody id="doctorTableBody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

        
        <section id="view-how-to-use" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">User Guidelines</span>
            </div>

            <div style="text-align: center; max-width: 760px; margin: 0 auto 32px;">
                <h1 style="font-size: 30px; font-weight: 800; color: var(--mq-primary); margin-bottom: 8px;">How MediQueue SA Works</h1>
                <p style="font-size: 15px; color: var(--mq-text-body);">Step-by-step operating guidelines for outpatients and healthcare professionals.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
                
                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-lg); padding: 32px;">
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--mq-primary); margin-bottom: 20px;">For Outpatients</h3>
                    
                    <div class="guide-step-row">
                        <div class="guide-step-num">1</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Symptom Check-In</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Enter your ID and describe your symptoms at facility entry or from home via mobile.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num">2</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Receive Live Ticket</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">The triage model assigns an urgency rating and allocates your digital ticket number.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num">3</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Wait Comfortably</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Track live position countdown on your device without remaining in crowded indoor rooms.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num">4</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Enter Consultation</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Proceed to the consultation room when your ticket is called by the physician.</p>
                        </div>
                    </div>
                </div>

                
                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-lg); padding: 32px;">
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--mq-sage); margin-bottom: 20px;">For Healthcare Staff</h3>
                    
                    <div class="guide-step-row">
                        <div class="guide-step-num" style="background: var(--mq-sage);">1</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Staff Verification</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Authenticate with your assigned Doctor ID and ward/hospital facility code.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num" style="background: var(--mq-sage);">2</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Review Prioritized Queue</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Patients appear sorted by clinical acuity and wait duration rather than arrival order alone.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num" style="background: var(--mq-sage);">3</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">One-Click Calling</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Click "Take Next Patient" to transition the patient to active consultation status.</p>
                        </div>
                    </div>

                    <div class="guide-step-row">
                        <div class="guide-step-num" style="background: var(--mq-sage);">4</div>
                        <div>
                            <h4 style="font-size: 15px; font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Complete or Refer</h4>
                            <p style="font-size: 13.5px; color: var(--mq-text-body);">Record brief clinical notes and complete the appointment or dispatch a specialist referral.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <section id="view-solutions" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">Platform Architecture</span>
            </div>

            <div style="text-align: center; max-width: 760px; margin: 0 auto 32px;">
                <h1 style="font-size: 30px; font-weight: 800; color: var(--mq-primary); margin-bottom: 8px;">Clinical Solutions &amp; Architecture</h1>
                <p style="font-size: 15px; color: var(--mq-text-body);">A resilient, decoupled microservice suite designed for public health infrastructure.</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--mq-primary); margin-bottom: 10px;">Virtual Transactional Queue</h3>
                    <p style="font-size: 14px; color: var(--mq-text-body); line-height: 1.6;">
                        High-availability transactional MySQL queue with composite priority scoring: combines clinical acuity weights with elapsed wait minutes to ensure fairness and clinical safety.
                    </p>
                </div>

                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--mq-primary); margin-bottom: 10px;">Natural Language Triage AI</h3>
                    <p style="font-size: 14px; color: var(--mq-text-body); line-height: 1.6;">
                        TF-IDF text feature extraction coupled with Logistic Regression classifiers evaluates patient-reported symptoms into High, Moderate, and Low urgency tiers, backed by instant keyword fallbacks.
                    </p>
                </div>

                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--mq-primary); margin-bottom: 10px;">Random Forest Wait Regressor</h3>
                    <p style="font-size: 14px; color: var(--mq-text-body); line-height: 1.6;">
                        Machine learning model trained on queue depth, consultation velocity, hour of day, and acuity distribution to return accurate estimated wait minutes.
                    </p>
                </div>

                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--mq-primary); margin-bottom: 10px;">POPIA Healthcare Governance</h3>
                    <p style="font-size: 14px; color: var(--mq-text-body); line-height: 1.6;">
                        Compliant with the Protection of Personal Information Act (POPIA). Identifiable health records remain encrypted, with only anonymized queue tickets presented publicly.
                    </p>
                </div>
            </div>
        </section>

        
        <section id="view-support" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">Technical Support</span>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 32px;">
                
                <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-lg); padding: 36px;">
                    <h3 style="font-size: 20px; font-weight: 800; color: var(--mq-primary); margin-bottom: 8px;">Facility Support &amp; Integration</h3>
                    <p style="font-size: 14px; color: var(--mq-text-muted); margin-bottom: 20px;">Submit onboarding inquiries, facility configuration, or report technical queue disruptions.</p>

                    <form onsubmit="handleSupportSubmit(event)">
                        <div class="input-row-group">
                            <label class="input-label-text">Hospital / Clinic Name</label>
                            <input type="text" class="text-input-field" placeholder="e.g. Tygerberg Hospital" required>
                        </div>
                        <div class="input-row-group">
                            <label class="input-label-text">Official Email</label>
                            <input type="email" class="text-input-field" placeholder="e.g. admin@facility.gov.za" required>
                        </div>
                        <div class="input-row-group">
                            <label class="input-label-text">Request Category</label>
                            <select class="text-input-field">
                                <option>Facility Onboarding &amp; Setup</option>
                                <option>Queue Sync &amp; Database Assistance</option>
                                <option>Clinical Triage Feedback</option>
                            </select>
                        </div>
                        <div class="input-row-group">
                            <label class="input-label-text">Message</label>
                            <textarea class="text-input-field" placeholder="Describe your inquiry..." required></textarea>
                        </div>
                        <button type="submit" class="btn-action-primary" style="width: 100%; justify-content: center;">
                            <span>Submit Administrative Request</span>
                        </button>
                    </form>
                </div>

                
                <div>
                    <div style="background: var(--mq-primary); color: #ffffff; border-radius: var(--mq-radius-lg); padding: 32px; margin-bottom: 24px;">
                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; opacity: 0.8;">Direct Operational Support</span>
                        <h4 style="font-size: 22px; font-weight: 800; margin: 8px 0 12px; color: #ffffff;">Administration Desk</h4>
                        <p style="font-size: 14px; opacity: 0.9; margin-bottom: 18px;">
                            For direct facility activation or urgent technical coordination, contact the engineering desk:
                        </p>
                        <div style="font-size: 18px; font-family: var(--mq-font-mono); font-weight: 700; margin-bottom: 16px;">
                            <a href="mailto:mediqueue.org@gmail.com" style="color: #ffffff; text-decoration: underline;">mediqueue.org@gmail.com</a>
                        </div>
                        <span style="font-size: 12px; opacity: 0.75;">Standard response time: Within 24 operational hours.</span>
                    </div>

                    <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-md); padding: 22px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #991b1b; margin-bottom: 8px;">Emergency Services Notice</h4>
                        <p style="font-size: 13px; color: var(--mq-text-body); line-height: 1.6;">
                            MediQueue SA coordinates non-emergency outpatient queues. If a patient requires immediate resuscitation or acute emergency care, dial <strong>112</strong> or <strong>10177</strong> immediately.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        
        <section id="view-about" class="view-frame">
            <div class="frame-nav-bar">
                <button class="btn-return-home" onclick="showView('home')">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                    <span>Back to Overview</span>
                </button>
                <span class="context-chip">Institutional Background</span>
            </div>

            <div style="background: var(--mq-surface); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-lg); padding: 44px; max-width: 900px; margin: 0 auto;">
                <span class="category-eyebrow" style="color:var(--mq-sage); margin-bottom: 8px;">PUBLIC HEALTH MISSION</span>
                <h1 style="font-size: 28px; font-weight: 800; color: var(--mq-primary); margin-bottom: 16px;">About MediQueue SA</h1>
                
                <p style="font-size: 15px; color: var(--mq-text-body); line-height: 1.7; margin-bottom: 20px;">
                    MediQueue SA is an enterprise healthcare queuing and triage system developed to address chronic outpatient congestion across South African healthcare facilities. In public hospitals, unpredictable waiting times and physical congestion not only compromise patient dignity but also exacerbate infection transmission risks.
                </p>

                <p style="font-size: 15px; color: var(--mq-text-body); line-height: 1.7; margin-bottom: 28px;">
                    By combining natural language symptom classification, dynamic machine-learning wait predictors, and a clean virtual queuing interface, MediQueue SA enables patients to reserve their place remotely, receive objective urgency prioritization, and arrive only when consultation is imminent.
                </p>

                <div style="border-top: 1px solid var(--mq-border-subtle); padding-top: 20px; font-size: 13px; color: var(--mq-text-muted);">
                    <strong>Academic &amp; Clinical Prototype Governance:</strong> The system utilizes synthetic clinical training sets to demonstrate end-to-end triage functionality. Real-world deployment is configured alongside hospital electronic health record (EHR) frameworks.
                </div>
            </div>
        </section>

    </main>

    
    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div>
                <strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)
            </div>
            <ul class="footer-links-row">
                <li><a href="#" onclick="showView('home'); return false;">Home</a></li>
                <li><a href="#" onclick="showView('how-to-use'); return false;">How It Works</a></li>
                <li><a href="#" onclick="showView('solutions'); return false;">Solutions</a></li>
                <li><a href="#" onclick="showView('support'); return false;">Support</a></li>
                <li><a href="#" onclick="showView('about'); return false;">About</a></li>
                <li><a href="login.php">Patient Login</a></li>
                <li><a href="doctor.php">Doctor Workspace</a></li>
                <li><a href="admin.php">Admin Analytics</a></li>
                <li><a href="mailto:mediqueue.org@gmail.com">Contact Support</a></li>
            </ul>
        </div>
    </footer>

    
    <script>
        const AppState = {
            activeView: 'home',
            doctorId: 1,
            doctorName: 'Dr. Thabo Mokoena',
            hospitalId: 1,
            hospitalName: 'Addington Hospital',
            completedCount: 0,
            activeConsultation: null,
            patientSession: null,
            queue: [],
            hospitals: [],
            doctors: [],
            ticketPollTimer: null,
            doctorPollTimer: null
        };
        document.addEventListener('DOMContentLoaded', () => {
            loadHospitals();
            try {
                const saved = sessionStorage.getItem('mq_patient_session');
                if (saved) {
                    AppState.patientSession = JSON.parse(saved);
                }
            } catch (e) {}
        });
        function showView(viewId) {
            AppState.activeView = viewId;
            if (viewId !== 'patient-ticket' && AppState.ticketPollTimer) {
                clearInterval(AppState.ticketPollTimer);
                AppState.ticketPollTimer = null;
            }
            if (viewId !== 'doctor-dashboard' && AppState.doctorPollTimer) {
                clearInterval(AppState.doctorPollTimer);
                AppState.doctorPollTimer = null;
            }
            document.querySelectorAll('.view-frame').forEach(frame => {
                frame.classList.remove('active');
            });
            const targetMap = {
                'home': 'view-home',
                'patient-portal': 'view-patient-portal',
                'patient-ticket': 'view-patient-ticket',
                'doctor-portal': 'view-doctor-portal',
                'doctor-dashboard': 'view-doctor-dashboard',
                'how-to-use': 'view-how-to-use',
                'solutions': 'view-solutions',
                'support': 'view-support',
                'about': 'view-about'
            };
            const targetId = targetMap[viewId] || 'view-home';
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                targetEl.classList.add('active');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            if (viewId === 'doctor-dashboard') {
                refreshDoctorTable();
                AppState.doctorPollTimer = setInterval(refreshDoctorTable, 6000);
            } else if (viewId === 'patient-ticket') {
                simulateTicketRefresh(true);
                AppState.ticketPollTimer = setInterval(() => simulateTicketRefresh(true), 8000);
            }
        }
        async function loadHospitals() {
            try {
                const res = await fetch('api.php?route=hospitals');
                const data = await res.json();
                if (data.success && Array.isArray(data.hospitals)) {
                    AppState.hospitals = data.hospitals;
                    const patientSelect = document.getElementById('formHospitalId');
                    const doctorSelect = document.getElementById('formDoctorHospital');
                    if (patientSelect) patientSelect.innerHTML = '';
                    if (doctorSelect) doctorSelect.innerHTML = '';
                    data.hospitals.forEach(h => {
                        const optP = document.createElement('option');
                        optP.value = h.id;
                        optP.textContent = `${h.name} (${h.city})`;
                        if (patientSelect) patientSelect.appendChild(optP);
                        const optD = document.createElement('option');
                        optD.value = h.id;
                        optD.textContent = `${h.name} (${h.city})`;
                        if (doctorSelect) doctorSelect.appendChild(optD);
                    });
                    if (data.hospitals.length > 0) {
                        const defaultId = data.hospitals[0].id;
                        AppState.hospitalId = defaultId;
                        AppState.hospitalName = data.hospitals[0].name;
                        loadDoctorListForHospital(defaultId);
                    }
                }
            } catch (err) {
                console.warn('Could not fetch hospitals from backend:', err);
            }
        }
        async function loadDoctorListForHospital(hospitalId) {
            const docSelect = document.getElementById('formDoctorId');
            if (!docSelect) return;
            docSelect.innerHTML = '<option value="">Loading assigned doctors...</option>';
            try {
                const res = await fetch(`api.php?route=hospital_doctors&hospital_id=${encodeURIComponent(hospitalId)}`);
                const data = await res.json();
                docSelect.innerHTML = '';
                if (data.success && Array.isArray(data.doctors) && data.doctors.length > 0) {
                    AppState.doctors = data.doctors;
                    data.doctors.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.id;
                        opt.textContent = `${d.full_name} (${d.available ? 'Available' : 'Busy'})`;
                        docSelect.appendChild(opt);
                    });
                    AppState.doctorId = data.doctors[0].id;
                    AppState.doctorName = data.doctors[0].full_name;
                } else {
                    docSelect.innerHTML = '<option value="1">Dr. On Duty (General Outpatient)</option>';
                }
            } catch (err) {
                console.warn('Could not fetch doctors:', err);
                docSelect.innerHTML = '<option value="1">Dr. On Duty (General Outpatient)</option>';
            }
        }
        async function handlePatientCheckIn(event) {
            event.preventDefault();
            const alertBox = document.getElementById('patientFormAlert');
            const submitBtn = document.getElementById('btnPatientSubmit');
            const patientId = document.getElementById('formPatientId').value.trim();
            const hospitalId = parseInt(document.getElementById('formHospitalId').value, 10) || 1;
            const symptoms = document.getElementById('formSymptoms').value.trim();
            if (!patientId || !symptoms) return;
            if (alertBox) { alertBox.style.display = 'none'; alertBox.className = ''; }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span>Connecting to AI Triage Engine...</span>`;
            }
            try {
                const triageRes = await fetch('api.php?route=triage', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ patient_id: patientId, symptoms: symptoms })
                });
                const triageData = await triageRes.json();
                if (!triageRes.ok || triageData.error) {
                    throw new Error(triageData.error || 'Failed to triage symptoms.');
                }
                const triageId = triageData.triage_id;
                const urgency = triageData.urgency || 'Moderate';
                if (submitBtn) submitBtn.innerHTML = `<span>Allocating Virtual Queue Number...</span>`;
                const queueRes = await fetch('api.php?route=queue_join', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        patient_id: patientId,
                        hospital_id: hospitalId,
                        triage_id: triageId
                    })
                });
                const queueData = await queueRes.json();
                let ticketCode = '';
                let queueEntryId = 0;
                let queueStatus = 'Waiting';
                if (queueRes.status === 409 && queueData.queue_number) {
                    ticketCode = queueData.queue_number;
                    queueEntryId = queueData.queue_id;
                    queueStatus = queueData.status || 'Waiting';
                } else if (!queueRes.ok || !queueData.success) {
                    throw new Error(queueData.error || 'Unable to join virtual queue.');
                } else {
                    ticketCode = queueData.queue_number;
                    queueEntryId = queueData.queue_id;
                    queueStatus = queueData.status;
                }
                const hospitalName = (AppState.hospitals.find(h => h.id == hospitalId) || {}).name || `Facility #${hospitalId}`;
                let position = 1;
                let estWait = 0;
                let patientsAhead = 0;
                try {
                    const qCheck = await fetch(`api.php?route=queue&hospital_id=${hospitalId}`);
                    const qData = await qCheck.json();
                    if (qData.success && Array.isArray(qData.queue)) {
                        const matched = qData.queue.find(item => item.queue_number === ticketCode || item.id === queueEntryId);
                        if (matched) {
                            position = matched.position || 1;
                            estWait = Math.round(matched.estimated_wait_minutes ?? 0);
                            patientsAhead = Math.max(0, position - 1);
                        } else {
                            position = qData.queue.filter(q => q.status === 'Waiting').length + 1;
                            patientsAhead = position - 1;
                            estWait = patientsAhead * 15;
                        }
                    }
                } catch (e) {
                    console.warn('Queue rank lookup notice:', e);
                }
                AppState.patientSession = {
                    ticket: ticketCode,
                    queueId: queueEntryId,
                    patientId: patientId,
                    hospitalId: hospitalId,
                    hospitalName: hospitalName,
                    urgency: urgency,
                    position: position,
                    patientsAhead: patientsAhead,
                    estWait: estWait,
                    status: queueStatus
                };
                try {
                    sessionStorage.setItem('mq_patient_session', JSON.stringify(AppState.patientSession));
                } catch (e) {}
                document.getElementById('ticketFacilityMeta').textContent = `${hospitalName.toUpperCase()} \u2022 OUTPATIENTS`;
                document.getElementById('displayTicketCode').textContent = ticketCode;
                document.getElementById('displayTicketRank').textContent = '#' + position;
                document.getElementById('displayTicketWait').textContent = `~${estWait} min`;
                document.getElementById('displayTicketAhead').textContent = `${patientsAhead} patient${patientsAhead === 1 ? '' : 's'} ahead`;
                const acuityEl = document.getElementById('displayTicketAcuity');
                acuityEl.textContent = urgency;
                if (urgency === 'High') {
                    acuityEl.style.color = 'var(--mq-urgency-high-text)';
                    document.getElementById('displayTicketScore').textContent = 'Priority Score: 75+ (High Acuity)';
                } else if (urgency === 'Moderate') {
                    acuityEl.style.color = 'var(--mq-sage)';
                    document.getElementById('displayTicketScore').textContent = 'Priority Score: 50 (Standard Acuity)';
                } else {
                    acuityEl.style.color = 'var(--mq-primary)';
                    document.getElementById('displayTicketScore').textContent = 'Priority Score: 25 (Routine Acuity)';
                }
                showView('patient-ticket');
            } catch (err) {
                if (alertBox) {
                    alertBox.textContent = err.message || 'An error occurred during submission.';
                    alertBox.style.display = 'block';
                    alertBox.style.background = '#fef2f2';
                    alertBox.style.border = '1px solid #fecaca';
                    alertBox.style.color = '#991b1b';
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `
                        <span>Submit Symptoms &amp; Generate Ticket</span>
                        <svg class="icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                    `;
                }
            }
        }
        async function simulateTicketRefresh(silent = false) {
            if (!AppState.patientSession) return;
            const session = AppState.patientSession;
            try {
                const res = await fetch(`api.php?route=queue&hospital_id=${session.hospitalId}`);
                const data = await res.json();
                if (data.success && Array.isArray(data.queue)) {
                    const matched = data.queue.find(item => item.queue_number === session.ticket || item.id === session.queueId);
                    const statusBadge = document.getElementById('displayTicketStatusBadge');
                    if (matched) {
                        session.position = matched.position || 1;
                        session.estWait = Math.round(matched.estimated_wait_minutes ?? 0);
                        session.patientsAhead = Math.max(0, session.position - 1);
                        session.status = matched.status;
                        document.getElementById('displayTicketRank').textContent = '#' + session.position;
                        document.getElementById('displayTicketWait').textContent = `~${session.estWait} min`;
                        document.getElementById('displayTicketAhead').textContent = `${session.patientsAhead} patient${session.patientsAhead === 1 ? '' : 's'} ahead`;
                        if (matched.status === 'In Consultation') {
                            if (statusBadge) {
                                statusBadge.textContent = 'CALLED \u2014 PLEASE ENTER ROOM';
                                statusBadge.style.background = 'var(--mq-sage)';
                                statusBadge.style.color = '#ffffff';
                            }
                        } else if (matched.status === 'Waiting') {
                            if (statusBadge) {
                                statusBadge.textContent = 'ACTIVE IN QUEUE';
                                statusBadge.style.background = 'rgba(255,255,255,0.15)';
                                statusBadge.style.color = '#ffffff';
                            }
                        }
                    } else {
                        if (statusBadge) {
                            statusBadge.textContent = 'CONSULTATION CONCLUDED';
                            statusBadge.style.background = 'var(--mq-sage)';
                            statusBadge.style.color = '#ffffff';
                        }
                        document.getElementById('displayTicketRank').textContent = 'DONE';
                        document.getElementById('displayTicketWait').textContent = '0 min';
                        document.getElementById('displayTicketAhead').textContent = 'Consultation finished';
                    }
                }
            } catch (err) {
                if (!silent) console.warn('Could not refresh ticket status:', err);
            }
        }
        function handleDoctorLogin(event) {
            event.preventDefault();
            const doctorSelect = document.getElementById('formDoctorId');
            const hospitalSelect = document.getElementById('formDoctorHospital');
            const doctorId = parseInt(doctorSelect.value, 10) || 1;
            const hospitalId = parseInt(hospitalSelect.value, 10) || 1;
            const doctorName = doctorSelect.options[doctorSelect.selectedIndex]?.text?.split('(')[0]?.trim() || `Dr. #${doctorId}`;
            const hospitalName = hospitalSelect.options[hospitalSelect.selectedIndex]?.text?.split('(')[0]?.trim() || `Facility #${hospitalId}`;
            AppState.doctorId = doctorId;
            AppState.doctorName = doctorName;
            AppState.hospitalId = hospitalId;
            AppState.hospitalName = hospitalName;
            document.getElementById('doctorSessionIndicator').textContent = `Authenticated: ${doctorName} (${hospitalName})`;
            document.getElementById('dashboardFacilityTitle').textContent = `${hospitalName} Clinical Queue Console`;
            showView('doctor-dashboard');
        }
        async function refreshDoctorTable() {
            try {
                const res = await fetch(`api.php?route=queue&hospital_id=${AppState.hospitalId}`);
                const data = await res.json();
                if (data.success && Array.isArray(data.queue)) {
                    AppState.queue = data.queue.filter(q => q.status === 'Waiting');
                    const inConsult = data.queue.find(q => q.status === 'In Consultation' && (parseInt(q.doctor_id, 10) === AppState.doctorId || !q.doctor_id));
                    AppState.activeConsultation = inConsult || null;
                    renderDoctorTable();
                    renderActiveConsultationRoom();
                }
                try {
                    const statsRes = await fetch(`api.php?route=doctor_stats&hospital_id=${AppState.hospitalId}&doctor_id=${AppState.doctorId}`);
                    const statsData = await statsRes.json();
                    if (statsData.success) {
                        AppState.completedCount = statsData.dealt_with ?? statsData.completed_today ?? 0;
                        document.getElementById('statCompletedQueue').textContent = AppState.completedCount;
                    }
                } catch (e) {}
            } catch (err) {
                console.warn('Error fetching clinical queue:', err);
            }
        }
        function renderDoctorTable() {
            const tbody = document.getElementById('doctorTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';
            document.getElementById('statWaitingQueue').textContent = AppState.queue.length;
            if (AppState.queue.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: var(--mq-text-muted); padding: 24px;">No patients currently waiting in queue.</td></tr>`;
            } else {
                AppState.queue.forEach(p => {
                    const tr = document.createElement('tr');
                    const urgencyClass = (p.effective_urgency || p.urgency || 'Low').toLowerCase();
                    const urgencyLabel = p.effective_urgency || p.urgency || 'Low';
                    const waitMin = Math.round(p.estimated_wait_minutes ?? p.waiting_minutes ?? 0);
                    const symptoms = p.symptoms || p.triage_notes || 'Patient awaiting initial consultation';
                    tr.innerHTML = `
                        <td style="font-family: var(--mq-font-mono); font-weight: 700; color: var(--mq-primary);">${p.queue_number}</td>
                        <td>${escapeHtml(p.full_name || 'Patient #' + p.patient_id)}</td>
                        <td><span class="urgency-pill ${urgencyClass}">${urgencyLabel}</span></td>
                        <td>~${waitMin}m</td>
                        <td style="max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(symptoms)}</td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }
        function renderActiveConsultationRoom() {
            const slot = document.getElementById('doctorActiveRoomSlot');
            if (!slot) return;
            if (!AppState.activeConsultation) {
                slot.innerHTML = `
                    <div style="text-align: center; padding: 24px 10px; color: var(--mq-text-muted);">
                        <div style="font-weight: 700; color: var(--mq-primary); margin-bottom: 4px;">Room Currently Idle</div>
                        <div style="font-size: 13px;">Click "Take Next Patient" above to call the next prioritized patient.</div>
                    </div>
                `;
            } else {
                const c = AppState.activeConsultation;
                const urgencyClass = (c.effective_urgency || c.urgency || 'Low').toLowerCase();
                const urgencyLabel = c.effective_urgency || c.urgency || 'Low';
                slot.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-family: var(--mq-font-mono); font-size: 20px; font-weight: 800; color: var(--mq-primary);">${c.queue_number}</span>
                        <span class="urgency-pill ${urgencyClass}">${urgencyLabel}</span>
                    </div>
                    <div style="font-size: 13.5px; color: var(--mq-text-body); margin-bottom: 12px;">
                        <div><strong>Patient:</strong> ${escapeHtml(c.full_name || 'Patient #' + c.patient_id)}</div>
                        <div style="margin-top: 4px;"><strong>Symptoms:</strong> ${escapeHtml(c.symptoms || 'Awaiting clinical notes')}</div>
                    </div>
                    <textarea id="activeConsultNotes" class="text-input-field" placeholder="Enter clinical assessment, diagnosis, or consultation notes..." style="min-height: 70px; font-size: 12.5px; margin-bottom: 10px;"></textarea>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn-action-primary" style="padding: 8px 14px; font-size: 13px;" onclick="doctorCompleteVisit()">Complete Visit</button>
                        <button class="btn-action-secondary" style="padding: 8px 14px; font-size: 13px;" onclick="doctorReferPatient()">Hospital Referral</button>
                    </div>
                `;
            }
        }
        async function doctorTakeNextPatient() {
            if (AppState.activeConsultation) {
                if (!confirm('You already have an active consultation open. Complete or refer the current patient first?')) {
                    return;
                }
            }
            try {
                const res = await fetch('api.php?route=queue_next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        hospital_id: AppState.hospitalId,
                        doctor_id: AppState.doctorId
                    })
                });
                const data = await res.json();
                if (!res.ok || data.error) {
                    alert(data.error || 'No patients currently waiting in this queue.');
                    return;
                }
                await refreshDoctorTable();
            } catch (err) {
                alert('Failed to call next patient: ' + err.message);
            }
        }
        async function doctorCompleteVisit() {
            if (!AppState.activeConsultation) return;
            const queueId = AppState.activeConsultation.id;
            const notes = document.getElementById('activeConsultNotes')?.value?.trim() || 'Consultation concluded.';
            try {
                const res = await fetch('api.php?route=queue_complete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId,
                        notes: notes
                    })
                });
                const data = await res.json();
                if (!res.ok || data.error) {
                    alert(data.error || 'Failed to complete visit.');
                    return;
                }
                AppState.activeConsultation = null;
                await refreshDoctorTable();
            } catch (err) {
                alert('Error completing consultation: ' + err.message);
            }
        }
        async function doctorReferPatient() {
            if (!AppState.activeConsultation) return;
            const queueId = AppState.activeConsultation.id;
            const reason = prompt('Enter clinical reason for hospital transfer/referral:');
            if (reason === null) return;
            try {
                const res = await fetch('api.php?route=queue_refer', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId,
                        notes: reason || 'Referred to regional hospital facility.'
                    })
                });
                const data = await res.json();
                if (!res.ok || data.error) {
                    alert(data.error || 'Failed to process referral.');
                    return;
                }
                alert('Patient has been successfully flagged for hospital referral.');
                AppState.activeConsultation = null;
                await refreshDoctorTable();
            } catch (err) {
                alert('Error processing referral: ' + err.message);
            }
        }
        function handleSupportSubmit(event) {
            event.preventDefault();
            alert('Your request has been submitted to the MediQueue SA Administration team.');
            showView('home');
        }
        function escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }
    </script>
</body>
</html>
