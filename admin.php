<?php
require_once __DIR__ . '/auth_common.php';
require_admin_page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Executive Health Analytics</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-main {
            display: block;
            max-width: 1240px;
        }
        .analytics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .bar-row {
            display: grid;
            grid-template-columns: 130px 1fr 50px;
            align-items: center;
            gap: 12px;
            margin: 14px 0;
            font-size: 13px;
            font-weight: 700;
        }
        .bar {
            height: 10px;
            border-radius: var(--mq-radius-full);
            background: var(--mq-border-subtle);
            overflow: hidden;
        }
        .bar i {
            display: block;
            height: 100%;
            border-radius: var(--mq-radius-full);
            background: var(--mq-sage);
        }
        .bar-row:nth-child(2) .bar i {
            background: var(--mq-status-amber);
        }
        .bar-row:nth-child(3) .bar i {
            background: var(--mq-status-red);
        }
        .alert-strip {
            margin-top: 10px;
            padding: 14px 18px;
            border-radius: var(--mq-radius-md);
            background: var(--mq-surface-alt);
            border: 1px solid var(--mq-border);
            color: var(--mq-text-body);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        @media (max-width: 860px) {
            .analytics-grid {
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
            <a href="main.php" class="brand-logo-container">
                <img src="assets/logo-transparent.png" alt="MediQueue SA Official Logo" class="official-logo-graphic" onerror="this.onerror=null; this.src='assets/logo.png';">
                <div class="brand-text-subtitle">
                    Republic of South Africa
                    <span>Virtual Healthcare Triage</span>
                </div>
            </a>
            <nav>
                <ul class="nav-links-cluster">
                    <li><a href="main.php" class="nav-item-btn">Home</a></li>
                    <li><a href="patient.php" class="nav-item-btn">Patient Portal</a></li>
                    <li><a href="doctor.php" class="nav-item-btn">Doctor Workspace</a></li>
                </ul>
            </nav>
            <div class="header-actions-group">
                <span class="live-pill">
                    <span class="status-dot-pulse"></span>
                    <span>ANALYTICS ENGINE</span>
                </span>
                <a href="main.php" class="btn-header-doctor">Exit Console</a>
            </div>
        </div>
    </header>

    <main class="admin-main">
        <section class="card">
            <div class="dashboard-title">
                <div>
                    <span class="eyebrow">Executive Health Intelligence</span>
                    <h2>Provincial Outpatient Analytics</h2>
                    <p id="period" style="margin-top: 4px;">Synchronizing active reporting metrics...</p>
                </div>
                <div class="avatar">
                    <svg class="icon" style="width: 20px; height: 20px;" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
            </div>

            <div class="kpi-grid">
                <div class="kpi">
                    <strong id="consultations">—</strong>
                    <span>Total Consultations (MTD)</span>
                </div>
                <div class="kpi">
                    <strong id="avoided">—</strong>
                    <span>Waiting Room Congestion Avoided</span>
                </div>
                <div class="kpi">
                    <strong id="avgWait">—</strong>
                    <span>Average Consult Wait Time</span>
                </div>
                <div class="kpi">
                    <strong id="referrals">—</strong>
                    <span>Inter-Facility Referrals</span>
                </div>
            </div>
        </section>

        <section class="analytics-grid">
            <section class="card" style="margin-bottom: 0;">
                <div class="dashboard-title" style="margin-bottom: 12px; padding-bottom: 8px;">
                    <div>
                        <span class="eyebrow">Acuity Distribution</span>
                        <h3>Triage Breakdown (SATS Standard)</h3>
                    </div>
                </div>
                <div id="triageBars">
                    <div class="empty">Loading triage distribution...</div>
                </div>
            </section>

            <section class="card" style="margin-bottom: 0;">
                <div class="dashboard-title" style="margin-bottom: 12px; padding-bottom: 8px;">
                    <div>
                        <span class="eyebrow">Epidemiological Tally</span>
                        <h3>Prevalent Chief Complaints</h3>
                    </div>
                </div>
                <div id="conditions">
                    <div class="empty">Loading prevalent conditions...</div>
                </div>
            </section>
        </section>

        <section class="card" style="padding: 16px 20px;">
            <div class="alert-strip">
                <svg class="icon" style="color: var(--mq-teal);" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Live operational data stream connected to MediQueue SA relational infrastructure. Metrics refresh automatically every 10 seconds.</span>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div><strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)</div>
            <ul class="footer-links-row">
                <li><a href="main.php">Home</a></li>
                <li><a href="patient.php">Patient Portal</a></li>
                <li><a href="doctor.php">Doctor Workspace</a></li>
                <li><a href="mailto:mediqueue.org@gmail.com">Contact Support</a></li>
            </ul>
        </div>
    </footer>

    <script>
        async function loadAdminStats() {
            try {
                const response = await fetch('api.php?route=admin_stats', { headers: { Accept: 'application/json' } });
                const data = await response.json();
                if (!response.ok || data.error) throw new Error(data.error || 'Could not load analytics');

                document.getElementById('period').textContent = 'Reporting Period: ' + data.month;
                document.getElementById('consultations').textContent = Number(data.consultations_this_month || 0).toLocaleString();
                document.getElementById('avoided').textContent = (data.hospital_visits_avoided_pct || 0) + '%';
                document.getElementById('avgWait').textContent = (data.avg_consult_wait_minutes || 0) + ' min';
                document.getElementById('referrals').textContent = Number(data.hospital_referrals || 0).toLocaleString();

                const triage = data.triage || { Low: 0, Moderate: 0, High: 0 };
                const max = Math.max(1, ...Object.values(triage).map(Number));
                const rows = [['Low Urgency', 'Low'], ['Moderate Urgency', 'Moderate'], ['High Urgency', 'High']];

                document.getElementById('triageBars').innerHTML = rows.map(([label, key]) => `
                    <div class="bar-row">
                        <span>${label}</span>
                        <div class="bar"><i style="width:${Math.round(Number(triage[key] || 0) / max * 100)}%"></i></div>
                        <b style="text-align: right;">${Number(triage[key] || 0)}</b>
                    </div>
                `).join('');

                const conditions = data.top_conditions || {};
                const entries = Object.entries(conditions);
                document.getElementById('conditions').innerHTML = entries.length ? entries.map(([name, count], i) => `
                    <div class="recordCard" style="margin-top: 6px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>${i + 1}. ${escapeHtml(name)}</strong>
                            <span style="font-weight: 700; color: var(--mq-primary);">${Number(count).toLocaleString()} cases</span>
                        </div>
                    </div>
                `).join('') : '<div class="empty">No symptoms logged in current reporting period.</div>';
            } catch (e) {
                document.getElementById('conditions').innerHTML = '<div class="error">' + escapeHtml(e.message) + '</div>';
            }
        }

        function escapeHtml(v) {
            return String(v ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", "&#039;");
        }

        window.addEventListener('DOMContentLoaded', () => {
            loadAdminStats();
            setInterval(loadAdminStats, 10000);
        });
    </script>

</body>
</html>
