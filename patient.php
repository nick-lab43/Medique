<?php
require_once __DIR__ . '/auth_common.php';
$patient = require_patient_page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Patient Care Dashboard</title>
    <link rel="stylesheet" href="style.css">
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
                    <li><a href="doctor.php" class="nav-item-btn">Doctor Workspace</a></li>
                    <li><a href="admin.php" class="nav-item-btn">Admin Analytics</a></li>
                </ul>
            </nav>
            <div class="header-actions-group">
                <span class="live-pill">
                    <span class="status-dot-pulse"></span>
                    <span>PATIENT VERIFIED</span>
                </span>
                <a href="logout.php" class="btn-header-doctor">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="card">
            <div class="dashboard-title">
                <div>
                    <span class="eyebrow">Patient Identity Gateway</span>
                    <h2>Good day, <?= htmlspecialchars($patient["full_name"]) ?></h2>
                    <p>Live virtual queue status, AI triage assessment, electronic prescriptions, and hospital referrals.</p>
                </div>
                <div class="avatar">
                    <?= htmlspecialchars(strtoupper(substr($patient["full_name"], 0, 1))) ?>
                </div>
            </div>

            <div class="queue-hero">
                <div class="queue-position">
                    <div class="label">Current Queue Position</div>
                    <div class="number" id="patientQueuePosition">#—</div>
                    <div class="muted" style="font-size: 11.5px;">Live outpatient position</div>
                </div>
                <div class="wait-badge">
                    <strong id="patientEstimatedWait">—</strong>
                    <span>Est. Wait</span>
                </div>
            </div>

            <div class="section-label">
                <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span>AI Clinical Triage Summary</span>
            </div>
            <div class="triage-box" id="triageSummary">
                <div class="muted">Submit your primary symptoms below to generate an objective clinical acuity assessment.</div>
            </div>

            <div class="section-label">
                <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Clinical Navigation</span>
            </div>
            <div class="quick-actions">
                <button onclick="document.getElementById('symptoms').focus()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    <span>Check Symptoms</span>
                </button>
                <button onclick="joinQueue()">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span>Join Queue</span>
                </button>
                <button onclick="showPrescriptions()">
                    <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    <span>Prescriptions</span>
                </button>
            </div>
            <div class="featureButtons">
                <button onclick="showRecords()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span>My Records</span>
                </button>
                <button onclick="startLiveGPS()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>Live GPS</span>
                </button>
                <button onclick="loadQueue()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    <span>Sync Queue</span>
                </button>
            </div>
        </section>

        <section class="card">
            <div class="dashboard-title">
                <div>
                    <span class="eyebrow">Outpatient Admission</span>
                    <h2>Consultation Setup</h2>
                </div>
            </div>

            <div class="patient-meta">
                <div class="meta-item">
                    <strong>Full Name</strong>
                    <span><?= htmlspecialchars($patient["full_name"]) ?></span>
                </div>
                <div class="meta-item">
                    <strong>Contact</strong>
                    <span><?= htmlspecialchars($patient["contact_number"] ?: "Not provided") ?></span>
                </div>
            </div>

            <input id="patientId" type="hidden" value="<?= (int)$patient["id"] ?>">

            <div>
                <label for="hospitalId">Healthcare Facility</label>
                <select id="hospitalId" onchange="loadHospitalDoctors()">
                    <option value="">Loading facilities...</option>
                </select>
            </div>

            <div>
                <label for="doctorId">Preferred Practitioner</label>
                <select id="doctorId">
                    <option value="">Choose a facility first</option>
                </select>
                <small class="formHint">Select your practitioner. Another available medical officer may call you to minimize waiting times.</small>
            </div>

            <div>
                <label for="symptoms">Symptoms &amp; Clinical Complaint</label>
                <textarea id="symptoms" placeholder="Describe your symptoms in natural language (e.g. sharp chest pain, shortness of breath, high fever)..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap;">
                <button class="primary" style="flex: 1;" onclick="submitTriage()">
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span>Assess Symptoms</span>
                </button>
                <button id="joinButton" class="sage" style="flex: 1;" onclick="joinQueue()" disabled>
                    <svg class="icon" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span>Join Virtual Queue</span>
                </button>
            </div>

            <div id="triageResult" style="margin-top: 12px;"></div>
            <div id="patientStatus" style="margin-top: 12px;"></div>
        </section>

        <section class="card full">
            <div class="queueHeader">
                <div>
                    <span class="eyebrow">Real-Time Service</span>
                    <h2>Active Facility Virtual Queue</h2>
                    <p>Synchronized continuously with facility electronic records.</p>
                </div>
                <button class="nav-item-btn" style="background: var(--mq-surface-alt);" onclick="loadQueue()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    <span>Refresh</span>
                </button>
            </div>
            <div id="queue">Loading live outpatient queue...</div>
        </section>

        <section class="card full">
            <div class="section-label">
                <svg class="icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Geographic Location &amp; Regional Hospital Transfer</span>
            </div>
            <div class="location-box">
                <label for="patientLocation" style="margin-top: 0;">Verified Patient Location</label>
                <input id="patientLocation" type="text" placeholder="Acquiring GPS location..." readonly>
                <div id="patientLocationStatus" style="margin-top: 10px;">
                    <div class="empty" style="padding: 10px;">GPS location service is disabled.</div>
                </div>
            </div>
            <div id="referralHospitals" class="featurePanel"></div>
        </section>

        <section class="card full">
            <div class="section-label">
                <svg class="icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <span>Electronic Health Records</span>
            </div>
            <div id="patientRecords" class="featurePanel"></div>
            <div id="patientCalendar" class="featurePanel"></div>
            <div id="medicationAlarms" class="featurePanel"></div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div><strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)</div>
            <ul class="footer-links-row">
                <li><a href="main.php">Home</a></li>
                <li><a href="doctor.php">Doctor Workspace</a></li>
                <li><a href="admin.php">Admin Analytics</a></li>
                <li><a href="mailto:mediqueue.org@gmail.com">Contact Support</a></li>
            </ul>
        </div>
    </footer>

    <script>
        let currentTriageId = null;
        let currentConsultationQueueId = null;

        async function api(route, method = "GET", data = null) {
            const url = "api.php?route=" + route;
            const options = {
                method,
                headers: {
                    "Accept": "application/json"
                }
            };

            if (data !== null) {
                options.headers["Content-Type"] = "application/json";
                options.body = JSON.stringify(data);
            }

            const response = await fetch(url, options);
            const raw = await response.text();
            let result;

            try {
                result = raw ? JSON.parse(raw) : {};
            } catch (parseError) {
                console.error("API returned non-JSON data:", raw);
                throw new Error("The server returned an invalid response.");
            }

            if (!response.ok) {
                throw new Error(result.error || "Request failed (HTTP " + response.status + ")");
            }

            return result;
        }

        async function loadHospitals() {
            const hospitalSelect = document.getElementById("hospitalId");
            try {
                const result = await api("hospitals");
                hospitalSelect.innerHTML = `<option value="">Choose a facility</option>` +
                    (result.hospitals || []).map(h =>
                        `<option value="${Number(h.id)}">${escapeHtml(h.name)} &bull; ${escapeHtml(h.city)}</option>`
                    ).join("");

                if (result.hospitals && result.hospitals.length) {
                    hospitalSelect.value = String(result.hospitals[0].id);
                    await loadHospitalDoctors();
                }
            } catch (error) {
                hospitalSelect.innerHTML = `<option value="">Could not load facilities</option>`;
            }
        }

        async function loadHospitalDoctors() {
            const hospitalId = document.getElementById("hospitalId").value;
            const doctorSelect = document.getElementById("doctorId");

            if (!hospitalId) {
                doctorSelect.innerHTML = `<option value="">Choose a facility first</option>`;
                return;
            }

            doctorSelect.innerHTML = `<option value="">Loading practitioners...</option>`;

            try {
                const result = await api("hospital_doctors&hospital_id=" + encodeURIComponent(hospitalId));
                const doctors = result.doctors || [];

                doctorSelect.innerHTML =
                    `<option value="">Choose your preferred practitioner</option>` +
                    doctors.map(d => `
                        <option value="${Number(d.id)}" ${Number(d.available) ? "" : "disabled"}>
                            ${escapeHtml(d.full_name)}${Number(d.available) ? " (Available)" : " (Unavailable)"}
                        </option>
                    `).join("");

                if (doctors.some(d => Number(d.available))) {
                    const first = doctors.find(d => Number(d.available));
                    doctorSelect.value = String(first.id);
                }
            } catch (error) {
                doctorSelect.innerHTML = `<option value="">Could not load practitioners</option>`;
            }
        }

        async function submitTriage() {
            const patientId = document.getElementById("patientId").value;
            const symptoms = document.getElementById("symptoms").value;

            if (!patientId) {
                alert("Enter a Patient ID.");
                return;
            }

            if (!symptoms.trim()) {
                alert("Please describe your symptoms.");
                return;
            }

            try {
                const result = await api("triage", "POST", {
                    patient_id: patientId,
                    symptoms: symptoms
                });

                currentTriageId = result.triage_id;

                const urgency = (result.urgency || 'Low').toLowerCase();
                document.getElementById("triageResult").innerHTML = `
                    <div class="result" style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>Triage Assessment Complete</strong>
                            <div style="font-size: 11.5px; color: var(--mq-text-muted);">Triage Reference: #${result.triage_id}</div>
                        </div>
                        <span class="urgency ${urgency}">${escapeHtml(result.urgency)}</span>
                    </div>
                `;

                const summaryBox = document.getElementById("triageSummary");
                if (summaryBox) {
                    summaryBox.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: var(--mq-primary);">Clinical Urgency: ${escapeHtml(result.urgency)}</div>
                                <div style="font-size: 12px; color: var(--mq-text-body); margin-top: 3px;">"${escapeHtml(symptoms)}"</div>
                            </div>
                            <span class="urgency ${urgency}">${escapeHtml(result.urgency)}</span>
                        </div>
                    `;
                }

                document.getElementById("joinButton").disabled = false;
            } catch (error) {
                alert(error.message);
            }
        }

        async function joinQueue() {
            const patientId = document.getElementById("patientId").value;
            const hospitalId = document.getElementById("hospitalId").value;
            const doctorId = document.getElementById("doctorId").value;

            if (!currentTriageId) {
                alert("Please assess symptoms first.");
                return;
            }

            if (!hospitalId) {
                alert("Please select a healthcare facility.");
                return;
            }

            if (!doctorId) {
                alert("Please select a practitioner.");
                return;
            }

            try {
                const result = await api("queue_join", "POST", {
                    patient_id: patientId,
                    triage_id: currentTriageId,
                    hospital_id: hospitalId,
                    doctor_id: doctorId
                });

                document.getElementById("patientStatus").innerHTML = `
                    <div class="success">
                        <strong>Successfully Joined Queue</strong>
                        <div style="margin-top: 4px; font-size: 12.5px;">
                            Ticket Number: <strong>${result.queue_number}</strong> &bull; 
                            Practitioner: <strong>${escapeHtml(result.doctor_name || "Assigned practitioner")}</strong>
                        </div>
                    </div>
                `;

                loadQueue();
            } catch (error) {
                alert(error.message);
            }
        }

        async function showRecords() {
            const patientId = document.getElementById("patientId").value;
            const panel = document.getElementById("patientRecords");
            panel.innerHTML = "<div class='empty'>Loading previous consultation records...</div>";

            try {
                const result = await api("patient_records&patient_id=" + encodeURIComponent(patientId));
                if (!result.records.length) {
                    panel.innerHTML = `<div class="empty">No prior consultation records registered on file.</div>`;
                    return;
                }

                panel.innerHTML = `
                    <h3 style="margin-bottom: 12px;">Prior Consultation History</h3>
                    ${result.records.map(r => `
                        <div class="recordCard">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong>${escapeHtml(r.doctor_name)}</strong>
                                <span style="font-size: 11px; color: var(--mq-text-muted);">${escapeHtml(r.created_at)}</span>
                            </div>
                            <span>Outcome: <strong>${escapeHtml(r.outcome)}</strong> &bull; Urgency: <strong>${escapeHtml(r.urgency)}</strong></span>
                            <span>Symptoms: ${escapeHtml(r.symptoms || "-")}</span>
                            <span>Clinical Notes: ${escapeHtml(r.notes || "None registered")}</span>
                        </div>
                    `).join("")}
                `;
            } catch (error) {
                panel.innerHTML = `<div class="error">${escapeHtml(error.message)}</div>`;
            }
        }

        async function showPrescriptions() {
            const patientId = document.getElementById("patientId").value;
            const panel = document.getElementById("patientRecords");
            panel.innerHTML = "<div class='empty'>Loading electronic prescriptions...</div>";

            try {
                const result = await api("prescriptions&patient_id=" + encodeURIComponent(patientId));
                if (!result.prescriptions.length) {
                    panel.innerHTML = `<div class="empty">No active electronic prescriptions on record.</div>`;
                    document.getElementById("patientCalendar").innerHTML = "";
                    document.getElementById("medicationAlarms").innerHTML = "";
                    return;
                }

                panel.innerHTML = `
                    <h3 style="margin-bottom: 6px;">Electronic Prescriptions</h3>
                    <p class="muted" style="margin-bottom: 12px;">Administer strictly according to medical instructions.</p>
                    ${result.prescriptions.map(p => `
                        <div class="recordCard">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong style="font-size: 14px; color: var(--mq-primary);">${escapeHtml(p.medication)}</strong>
                                <span style="font-size: 11.5px; color: var(--mq-sage); font-weight: 700;">Prescribed by ${escapeHtml(p.doctor_name)}</span>
                            </div>
                            <span>Description: ${escapeHtml(p.instructions || "-")}</span>
                            <span>Dosage: ${escapeHtml(p.dose_amount || "-")} ${escapeHtml(p.dose_unit || "")} &bull; Quantity: ${escapeHtml(p.quantity ?? "-")} ${escapeHtml(p.quantity_unit || "")}</span>
                            <span>Frequency: ${escapeHtml(p.times_per_day ?? "-")} time(s) daily &bull; Scheduled times: ${escapeHtml(p.medication_times || "See instructions")}</span>
                            <span>Course Duration: ${escapeHtml(p.start_date || "-")} through ${escapeHtml(p.end_date || "-")}</span>
                            ${Number(p.refill_required) ? `<span>Prescription Refill Scheduled: <strong>${escapeHtml(p.refill_date || "Date pending")}</strong></span>` : ""}
                            ${Number(p.checkup_required) ? `<span>Follow-up Review Scheduled: <strong>${escapeHtml(p.checkup_date || "Date pending")}</strong></span>` : ""}
                            ${p.followup_notes ? `<span>Notes: ${escapeHtml(p.followup_notes)}</span>` : ""}
                        </div>
                    `).join("")}
                `;
                renderPatientCalendar(result.prescriptions);
                setupMedicationAlarms(result.prescriptions);
            } catch (error) {
                panel.innerHTML = `<div class="error">${escapeHtml(error.message)}</div>`;
            }
        }

        function parseTimes(value) {
            return String(value || "").split(",").map(x => x.trim()).filter(x => /^([01]\d|2[0-3]):[0-5]\d$/.test(x));
        }

        function renderPatientCalendar(prescriptions) {
            const dates = {};
            prescriptions.forEach(p => {
                [p.refill_date, p.checkup_date].forEach((d, i) => {
                    if (d) {
                        dates[d] = dates[d] || [];
                        dates[d].push(i === 0 ? "Prescription Refill" : "Clinical Review");
                    }
                });
            });
            const days = Object.keys(dates).sort();
            const panel = document.getElementById("patientCalendar");
            panel.innerHTML = `
                <h3 style="margin-top: 18px; margin-bottom: 8px;">Scheduled Clinical Calendar</h3>
                ${days.length ? days.map(d => `
                    <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--mq-surface-alt); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-sm); margin-bottom: 6px; font-size: 13px;">
                        <strong>${escapeHtml(d)}</strong>
                        <span style="color: var(--mq-sage); font-weight: 700;">${escapeHtml(dates[d].join(" &bull; "))}</span>
                    </div>
                `).join("") : `<div class="empty">No calendar appointments scheduled.</div>`}
            `;
        }

        let medicationAlarmTimer = null;
        function setupMedicationAlarms(prescriptions) {
            const panel = document.getElementById("medicationAlarms");
            const alarms = [];
            prescriptions.forEach(p => parseTimes(p.medication_times).forEach(time => alarms.push({ medication: p.medication, time, start: p.start_date, end: p.end_date })));
            panel.innerHTML = `
                <h3 style="margin-top: 18px; margin-bottom: 8px;">Medication Administration Schedule</h3>
                ${alarms.length ? `
                    <p class="muted" style="margin-bottom: 10px;">Alerts are calibrated to your clinical prescription schedule.</p>
                    ${alarms.map(a => `
                        <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--mq-surface-alt); border: 1px solid var(--mq-border-subtle); border-radius: var(--mq-radius-sm); margin-bottom: 6px; font-size: 13px;">
                            <strong style="font-family: var(--mq-font-mono);">${escapeHtml(a.time)}</strong>
                            <span>${escapeHtml(a.medication)}</span>
                        </div>
                    `).join("")}
                    <div style="margin-top: 10px;">
                        <button class="secondary" onclick="enableMedicationNotifications()">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            <span>Enable System Reminders</span>
                        </button>
                    </div>
                ` : `<div class="empty">No administration schedule defined.</div>`}
            `;
            if (medicationAlarmTimer) clearInterval(medicationAlarmTimer);
            medicationAlarmTimer = setInterval(() => checkMedicationAlarms(alarms), 15000);
            checkMedicationAlarms(alarms);
        }

        async function enableMedicationNotifications() {
            if (!("Notification" in window)) return alert("This browser environment does not support push notifications.");
            const permission = await Notification.requestPermission();
            alert(permission === "granted" ? "Medication reminders enabled." : "Notifications were not granted.");
        }

        function checkMedicationAlarms(alarms) {
            const now = new Date();
            const hhmm = String(now.getHours()).padStart(2, "0") + ":" + String(now.getMinutes()).padStart(2, "0");
            const today = now.toISOString().slice(0, 10);
            alarms.filter(a => a.time === hhmm && (!a.start || today >= a.start) && (!a.end || today <= a.end)).forEach(a => {
                const key = `mq-alarm-${today}-${a.time}-${a.medication}`;
                if (localStorage.getItem(key)) return;
                localStorage.setItem(key, "1");
                alert(`Medication Reminder: ${a.medication}\nScheduled Time: ${a.time}`);
                if ("Notification" in window && Notification.permission === "granted") {
                    new Notification("MediQueue SA Reminder", { body: `Scheduled dose: ${a.medication} at ${a.time}.` });
                }
            });
        }

        let gpsWatchId = null;
        let lastGpsSentAt = 0;

        async function loadReferralHospitals(queueId) {
            const panel = document.getElementById("referralHospitals");
            if (!panel || !queueId) return;

            panel.innerHTML = `<div class="empty">Loading geodesic distances to nearby referral hospitals...</div>`;

            try {
                const result = await api("referral_hospitals&queue_id=" + encodeURIComponent(queueId));

                if (!result.gps_available) {
                    panel.innerHTML = `
                        <div class="empty">
                            Hospital transfer referral recorded.<br>
                            Enable Live GPS so MediQueue can calculate nearest Durban tertiary facilities.
                            <br><button class="secondary" style="margin-top: 8px;" onclick="startLiveGPS()">Activate Live GPS</button>
                        </div>`;
                    return;
                }

                if (!result.hospitals || !result.hospitals.length) {
                    panel.innerHTML = `
                        <div class="empty">
                            Hospital referral logged. No regional facilities found within 60 km radius.
                        </div>`;
                    return;
                }

                panel.innerHTML = `
                    <h3 style="margin-bottom: 6px;">Select Preferred Referral Facility</h3>
                    <p class="muted" style="margin-bottom: 12px;">Sorted strictly by geographic proximity to your live location.</p>
                    ${result.hospitals.map((h, index) => `
                        <div class="recordCard">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong>${index + 1}. ${escapeHtml(h.name)}</strong>
                                <span style="font-weight: 700; color: var(--mq-primary);">${escapeHtml(h.distance_km)} km</span>
                            </div>
                            <span style="font-size: 12px; color: var(--mq-text-muted);">${escapeHtml(h.city)}</span>
                            <div style="display: flex; gap: 8px; margin-top: 6px;">
                                <button class="primary" style="padding: 6px 12px; font-size: 12px;" onclick="chooseReferralHospital(${Number(h.id)}, '${escapeHtml(h.name).replace(/'/g, "\\'")}', ${Number(queueId)})">
                                    Select Facility
                                </button>
                                <a href="${escapeHtml(h.directions_url)}" target="_blank" rel="noopener" class="nav-item-btn" style="background: var(--mq-surface); font-size: 12px;">
                                    View Directions
                                </a>
                            </div>
                        </div>
                    `).join("")}
                    <div id="selectedReferralHospital" class="success" style="margin-top: 12px;">No referral facility selected yet.</div>
                `;
            } catch (error) {
                panel.innerHTML = `<div class="error">Could not load referral facilities: ${escapeHtml(error.message)}</div>`;
            }
        }

        async function chooseReferralHospital(hospitalId, hospitalName, queueId) {
            if (!confirm(`Confirm ${hospitalName} as your transfer destination?`)) return;

            try {
                const result = await api("choose_referral_hospital", "POST", {
                    queue_id: queueId,
                    hospital_id: hospitalId
                });

                const panel = document.getElementById("selectedReferralHospital");
                if (panel) {
                    panel.innerHTML = `<strong>Selected Destination Facility:</strong> ${escapeHtml(result.hospital.name)}<br><span style="font-size: 12px;">Transfer registered. Present at outpatient admissions upon arrival.</span>`;
                }
            } catch (error) {
                alert("Could not select facility: " + error.message);
            }
        }

        async function loadLocation() {
            const patientId = document.getElementById("patientId").value;
            try {
                const result = await api("patient_location&patient_id=" + encodeURIComponent(patientId));
                if (result.location) {
                    document.getElementById("patientLocation").value = result.location;
                }
            } catch (error) {}
        }

        async function updateLiveGPS(position) {
            const now = Date.now();
            if (now - lastGpsSentAt < 10000) return;
            lastGpsSentAt = now;

            const patientId = document.getElementById("patientId").value;
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const accuracy = position.coords.accuracy;

            let placeName = "Current patient location";
            try {
                const geo = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(latitude)}&lon=${encodeURIComponent(longitude)}&zoom=18&addressdetails=1`,
                    { headers: { "Accept": "application/json", "Accept-Language": "en" } }
                );
                if (geo.ok) {
                    const geoData = await geo.json();
                    placeName = geoData.display_name || placeName;
                }
            } catch (e) {}

            document.getElementById("patientLocation").value = placeName;
            document.getElementById("patientLocationStatus").innerHTML = `
                <div class="success" style="font-size: 12px;">
                    Active GPS coordinates synchronized &bull; Accuracy: &plusmn;${Math.round(accuracy)} m
                </div>
            `;

            try {
                await api("patient_location", "POST", {
                    patient_id: patientId,
                    latitude: latitude,
                    longitude: longitude,
                    location: placeName,
                    accuracy_meters: accuracy
                });
            } catch (error) {}
        }

        function startLiveGPS() {
            if (!navigator.geolocation) {
                alert("This device does not support GPS geolocation.");
                return;
            }

            if (gpsWatchId !== null) {
                document.getElementById("patientLocationStatus").innerHTML = `<div class="success" style="font-size: 12px;">Live GPS positioning is active.</div>`;
                return;
            }

            document.getElementById("patientLocationStatus").innerHTML = `<div class="empty" style="padding: 10px;">Requesting device GPS authorization...</div>`;

            gpsWatchId = navigator.geolocation.watchPosition(
                updateLiveGPS,
                function(error) {
                    let message = "Unable to acquire GPS coordinates.";
                    if (error.code === 1) message = "Location permission was denied.";
                    document.getElementById("patientLocationStatus").innerHTML = `<div class="error" style="font-size: 12px;">${message}</div>`;
                },
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 15000 }
            );
        }

        window.addEventListener("beforeunload", function () {
            if (gpsWatchId !== null) navigator.geolocation.clearWatch(gpsWatchId);
        });

        async function loadQueue() {
            const hospitalId = document.getElementById("hospitalId").value.trim();
            if (!hospitalId) return;

            try {
                const result = await api("queue&hospital_id=" + encodeURIComponent(hospitalId));
                displayQueue(result.queue || []);

                const patientId = document.getElementById("patientId").value;
                const referral = (result.queue || []).find(
                    item => String(item.patient_id) === String(patientId) && item.status === "Referral"
                );
                if (referral) {
                    loadReferralHospitals(referral.id);
                }
            } catch (error) {
                document.getElementById("queue").innerHTML = `<div class="error">${escapeHtml(error.message)}</div>`;
            }
        }

        function escapeHtml(value) {
            return String(value ?? "")
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        function displayQueue(queue) {
            const container = document.getElementById("queue");
            const myPatientId = String(document.getElementById("patientId").value);
            const mine = queue.find(item => String(item.patient_id) === myPatientId);
            const positionEl = document.getElementById("patientQueuePosition");
            const waitEl = document.getElementById("patientEstimatedWait");

            if (positionEl) positionEl.textContent = mine ? "#" + (mine.position ?? "—") : "#—";
            if (waitEl) waitEl.textContent = mine ? ("~" + Math.round(mine.estimated_wait_minutes ?? 0) + " min") : "—";

            if (!queue.length) {
                container.innerHTML = `<div class="empty">No patients currently in queue.</div>`;
                return;
            }

            container.innerHTML = queue.map(patient => {
                const urgency = (patient.effective_urgency || 'Low').toLowerCase();
                const isMine = String(patient.patient_id) === myPatientId;
                const referralHospital = patient.referral_hospital_name || "Facility selection pending";

                return `
                <div class="queueItem" style="${isMine ? 'border: 2px solid var(--mq-sage); background: #fdfefd;' : ''}">
                    <div class="queueNumber">${escapeHtml(patient.queue_number)}</div>
                    <div class="patientInfo">
                        <strong>${escapeHtml(patient.full_name)} ${isMine ? '<span style="color: var(--mq-sage); font-weight: 800;">(You)</span>' : ''}</strong>
                        <span>Practitioner: ${escapeHtml(patient.doctor_name || "Assigned on call")}</span>
                        <span>Rank: #${escapeHtml(patient.position ?? "-")} &bull; Wait duration: ${escapeHtml(patient.waiting_minutes)}m</span>
                        <span>${patient.status === "Referral" ? `Transfer: <strong>${escapeHtml(referralHospital)}</strong>` : `Est. Wait: ~${Math.round(patient.estimated_wait_minutes ?? 0)} min`}</span>
                    </div>
                    <div>
                        <span class="urgency ${urgency}">${escapeHtml(patient.effective_urgency)}</span>
                    </div>
                    <div>
                        <span style="font-size: 11px; color: var(--mq-text-muted);">Priority:</span>
                        <strong style="color: var(--mq-primary);">${escapeHtml(patient.priority_score)}</strong>
                    </div>
                    <div>
                        <span class="status">${escapeHtml(patient.status)}</span>
                        ${patient.status === "Referral" ? `<div style="font-size: 10.5px; color: var(--mq-status-red); margin-top: 4px;">Transfer logged</div>` : ""}
                    </div>
                </div>`;
            }).join("");
        }

        window.addEventListener("DOMContentLoaded", function () {
            loadHospitals();
            loadLocation();
            startLiveGPS();
            loadQueue();
            setInterval(loadQueue, 3000);
        });
    </script>

</body>
</html>
