<?php
require_once __DIR__ . '/auth_common.php';
$authenticatedDoctor = require_doctor_page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQueue SA | Clinical Practitioner Workspace</title>
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
                    <li><a href="login.php" class="nav-item-btn">Patient Portal</a></li>
                    <li><a href="admin.php" class="nav-item-btn">Admin Analytics</a></li>
                </ul>
            </nav>
            <div class="header-actions-group">
                <span class="live-pill">
                    <span class="status-dot-pulse"></span>
                    <span>LIVE QUEUE ACTIVE</span>
                </span>
                <a href="main.php" class="btn-header-doctor">Exit Console</a>
            </div>
        </div>
    </header>

    <main>
        <section class="card">
            <div class="dashboard-title">
                <div>
                    <span class="eyebrow">Clinical Station</span>
                    <h2>Doctor Workspace</h2>
                    <p>Live prioritized queue management, automated triage routing, electronic consultations, and hospital transfers.</p>
                </div>
                <div class="avatar">
                    <svg class="icon" style="width: 20px; height: 20px;" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
            </div>

            <div>
                <label for="doctorHospitalId">Healthcare Facility</label>
                <select id="doctorHospitalId" onchange="loadDoctorOptions()">
                    <option value="">Loading facilities...</option>
                </select>
            </div>

            <div>
                <label for="doctorId">Medical Practitioner</label>
                <select id="doctorId" onchange="loadQueue()">
                    <option value="">Choose clinical practitioner</option>
                </select>
                <small class="formHint">Practitioners are assigned to their respective facility. You may call any prioritized patient in your active facility queue.</small>
            </div>

            <div class="doctorActionGrid">
                <button class="primary" onclick="nextPatient()">
                    <svg class="icon" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    <span>Take Next Patient</span>
                </button>
                <button class="secondary" onclick="loadStats()">
                    <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="12" width="4" height="8"/><rect x="10" y="8" width="4" height="12"/><rect x="17" y="4" width="4" height="16"/></svg>
                    <span>Refresh Statistics</span>
                </button>
            </div>

            <div id="doctorStats" class="statsGrid"></div>
            <div id="doctorStatus"></div>
            <div id="consultationControls"></div>
        </section>

        <section class="card">
            <div class="dashboard-title">
                <div>
                    <span class="eyebrow">Outpatient Order</span>
                    <h2>Virtual Queue</h2>
                    <p>Sorted dynamically by triage severity, clinical escalation rules, and waiting time.</p>
                </div>
                <button class="nav-item-btn" style="background: var(--mq-surface-alt);" onclick="loadQueue()">
                    <svg class="icon" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    <span>Sync</span>
                </button>
            </div>
            <div id="nextPatientCard" class="next-patient-card">Select a facility and medical officer to view the next patient.</div>
            <div id="queue">Loading outpatient queue...</div>
        </section>

        <section class="card full">
            <div class="queueHeader">
                <div>
                    <span class="eyebrow">Clinical Guidelines</span>
                    <h2>Emergency Escalation &amp; Inter-Facility Transfer</h2>
                    <p>All triaged cases follow the South African Triage Scale (SATS) emergency triage scoring protocol.</p>
                </div>
            </div>
            <div class="recordCard">
                <strong>Regional Hospital Referrals</strong>
                <span>When a clinical case requires secondary or tertiary specialized care, MediQueue SA calculates geodesic distances to regional referral hospitals (e.g. Inkosi Albert Luthuli Central, Addington, Prince Mshiyeni) and presents optimal transfer routing.</span>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-inner-flex">
            <div><strong>MediQueue SA</strong> &mdash; Clinical Queuing &amp; Virtual Triage Platform (South Africa)</div>
            <ul class="footer-links-row">
                <li><a href="main.php">Home</a></li>
                <li><a href="login.php">Patient Portal</a></li>
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

        async function loadDoctorHospitals() {
            const hospitalSelect = document.getElementById("doctorHospitalId");
            try {
                const result = await api("hospitals");
                hospitalSelect.innerHTML = `<option value="">Choose a facility</option>` +
                    (result.hospitals || []).map(h =>
                        `<option value="${Number(h.id)}">${escapeHtml(h.name)} &bull; ${escapeHtml(h.city)}</option>`
                    ).join("");

                if (result.hospitals && result.hospitals.length) {
                    hospitalSelect.value = String(result.hospitals[0].id);
                    await loadDoctorOptions();
                }
            } catch (error) {
                hospitalSelect.innerHTML = `<option value="">Could not load facilities</option>`;
            }
        }

        async function loadDoctorOptions() {
            const hospitalId = document.getElementById("doctorHospitalId").value;
            const doctorSelect = document.getElementById("doctorId");

            if (!hospitalId) {
                doctorSelect.innerHTML = `<option value="">Choose a facility first</option>`;
                return;
            }

            doctorSelect.innerHTML = `<option value="">Loading assigned practitioners...</option>`;

            try {
                const result = await api("hospital_doctors&hospital_id=" + encodeURIComponent(hospitalId));
                const doctors = result.doctors || [];

                doctorSelect.innerHTML =
                    `<option value="">Choose clinical practitioner</option>` +
                    doctors.map(d => `
                        <option value="${Number(d.id)}" ${Number(d.available) ? "" : "disabled"}>
                            ${escapeHtml(d.full_name)}${Number(d.available) ? " (Available)" : " (Unavailable)"}
                        </option>
                    `).join("");

                const available = doctors.find(d => Number(d.available));
                if (available) {
                    doctorSelect.value = String(available.id);
                }

                loadQueue();
                loadStats();
            } catch (error) {
                doctorSelect.innerHTML = `<option value="">Could not load practitioners</option>`;
            }
        }

        async function callPatient(queueId) {
            const doctorId = document.getElementById("doctorId").value;
            const hospitalId = document.getElementById("doctorHospitalId").value;

            if (!doctorId || !hospitalId) {
                alert("Choose your practitioner and facility first.");
                return;
            }

            if (!confirm("Call this patient into consultation?")) return;

            try {
                const result = await api("queue_call_patient", "POST", {
                    doctor_id: doctorId,
                    hospital_id: hospitalId,
                    queue_id: queueId
                });

                currentConsultationQueueId = result.queue_id;

                document.getElementById("doctorStatus").innerHTML = `
                    <div class="success">
                        <div><strong>Patient Called Successfully</strong></div>
                        <div style="margin-top: 4px; font-size: 12.5px;">
                            Ticket: <strong>${escapeHtml(result.queue_number)}</strong> &bull; 
                            Patient ID: <strong>${escapeHtml(result.patient_id)}</strong> &bull; 
                            Status: <strong>In Consultation</strong>
                        </div>
                    </div>`;

                document.getElementById("consultationControls").innerHTML = `
                    <div style="margin-top: 14px;">
                        <textarea id="consultationNotes" placeholder="Enter clinical consultation notes, observations, or diagnosis..."></textarea>
                        <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
                            <button class="primary" onclick="completeConsultation()">
                                <svg class="icon" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Complete Visit</span>
                            </button>
                            <button class="secondary" onclick="addPrescription()">
                                <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                <span>Issue Prescription</span>
                            </button>
                            <button class="danger" onclick="referPatient()">
                                <svg class="icon" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                <span>Transfer / Refer</span>
                            </button>
                        </div>
                    </div>
                `;

                loadQueue();
            } catch (error) {
                alert(error.message);
            }
        }

        async function loadQueue() {
            const hospitalId = document.getElementById("doctorHospitalId").value.trim();
            if (!hospitalId) return;

            try {
                const result = await api("queue&hospital_id=" + encodeURIComponent(hospitalId));
                displayQueue(result.queue || []);
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
            const nextCard = document.getElementById("nextPatientCard");
            const next = queue.find(item => item.status === "Waiting");

            if (nextCard) {
                nextCard.innerHTML = next
                    ? `<div class="next-patient-head"><span>Next In Line</span><b>${escapeHtml(next.queue_number)}</b></div>
                       <strong>${escapeHtml(next.full_name)}</strong>
                       <span style="font-size: 12px; color: var(--mq-text-muted);">${escapeHtml(next.effective_urgency)} Urgency &bull; Waiting ${escapeHtml(next.waiting_minutes)} min &bull; Estimated wait: ~${escapeHtml(next.estimated_wait_minutes ?? "-")} min</span>
                       <div style="margin-top: 8px;">
                           <button class="primary" style="padding: 7px 14px; font-size: 12px;" onclick="callPatient(${Number(next.id)})">
                               <svg class="icon" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                               <span>Call This Patient</span>
                           </button>
                       </div>`
                    : `<strong>No Patients Currently Waiting</strong><span>The queue is clear for this facility. New outpatient admissions will populate here automatically.</span>`;
            }

            if (!queue.length) {
                container.innerHTML = `<div class="empty">No active patients in queue.</div>`;
                return;
            }

            container.innerHTML = queue.map(patient => {
                const urgency = (patient.effective_urgency || 'Low').toLowerCase();
                return `
                <div class="queueItem">
                    <div class="queueNumber">${escapeHtml(patient.queue_number)}</div>
                    <div class="patientInfo">
                        <strong>${escapeHtml(patient.full_name)}</strong>
                        <span>Rank: #${escapeHtml(patient.position ?? "-")} &bull; Wait: ${escapeHtml(patient.waiting_minutes)}m (Est: ~${escapeHtml(patient.estimated_wait_minutes ?? "-")}m)</span>
                        <span>Facility Location: <strong>${escapeHtml(patient.location ?? "GPS coordinates pending")}</strong></span>
                    </div>
                    <div>
                        <span class="urgency ${urgency}">${escapeHtml(patient.effective_urgency)}</span>
                    </div>
                    <div>
                        <span style="font-size: 11px; color: var(--mq-text-muted);">Score:</span>
                        <strong style="color: var(--mq-primary);">${escapeHtml(patient.priority_score)}</strong>
                    </div>
                    <div>
                        <span class="status">${escapeHtml(patient.status)}</span>
                        ${patient.status === "Waiting" ? `
                        <button class="secondary" style="margin-top: 6px; padding: 6px 10px; font-size: 11.5px; width: 100%;" onclick="callPatient(${Number(patient.id)})">
                            <svg class="icon" style="width: 13px; height: 13px;" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            <span>Call Patient</span>
                        </button>` : ""}
                    </div>
                </div>`;
            }).join("");
        }

        async function nextPatient() {
            const doctorId = document.getElementById("doctorId").value;
            const hospitalId = document.getElementById("doctorHospitalId").value;

            if (!doctorId) {
                alert("Please select a medical practitioner.");
                return;
            }
            if (!hospitalId) {
                alert("Please select a healthcare facility.");
                return;
            }

            try {
                const result = await api("queue_next", "POST", {
                    doctor_id: doctorId,
                    hospital_id: hospitalId
                });

                currentConsultationQueueId = result.queue_id;

                document.getElementById("doctorStatus").innerHTML = `
                    <div class="success">
                        <div><strong>Next Patient Called into Consultation</strong></div>
                        <div style="margin-top: 4px; font-size: 12.5px;">
                            Ticket: <strong>${result.queue_number}</strong> &bull; 
                            Patient ID: <strong>${result.patient_id}</strong> &bull; 
                            Status: <strong>In Consultation</strong>
                        </div>
                    </div>`;

                document.getElementById("consultationControls").innerHTML = `
                    <div style="margin-top: 14px;">
                        <textarea id="consultationNotes" placeholder="Enter clinical consultation notes, observations, or diagnosis..."></textarea>
                        <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
                            <button class="primary" onclick="completeConsultation()">
                                <svg class="icon" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Complete Visit</span>
                            </button>
                            <button class="secondary" onclick="addPrescription()">
                                <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                <span>Issue Prescription</span>
                            </button>
                            <button class="danger" onclick="referPatient()">
                                <svg class="icon" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                                <span>Transfer / Refer</span>
                            </button>
                        </div>
                    </div>`;

                loadQueue();
            } catch (error) {
                alert(error.message);
            }
        }

        async function loadStats() {
            const doctorId = document.getElementById("doctorId").value.trim();
            if (!doctorId) return;

            try {
                const result = await api("doctor_stats&doctor_id=" + encodeURIComponent(doctorId));
                document.getElementById("doctorStats").innerHTML = `
                    <div class="statCard"><strong>${result.patients_today}</strong><span>Admitted Today</span></div>
                    <div class="statCard"><strong>${result.dealt_with}</strong><span>Consulted</span></div>
                    <div class="statCard"><strong>${result.waiting}</strong><span>Waiting</span></div>
                    <div class="statCard"><strong>${result.in_consultation}</strong><span>Active Room</span></div>
                `;
            } catch (error) {
                document.getElementById("doctorStats").innerHTML = `<div class="error">${escapeHtml(error.message)}</div>`;
            }
        }

        async function addPrescription() {
            if (!currentConsultationQueueId) return;

            const medication = prompt("Medication name (e.g. Amoxicillin, Paracetamol):");
            if (!medication || !medication.trim()) return;
            const instructions = prompt("Instructions (e.g. Take with food):") || "";
            const doseAmount = prompt("Dose quantity (e.g. 2):") || "";
            const doseUnit = prompt("Dose unit (tablets, ml, capsules):") || "";
            const quantity = prompt("Total quantity supplied:") || "";
            const quantityUnit = prompt("Quantity unit (tablets, bottles):") || "";
            const timesPerDay = prompt("Times per day (e.g. 3):") || "";
            const durationValue = prompt("Duration (number):") || "";
            const durationUnit = prompt("Duration unit (days, weeks):") || "";
            const medicationTimes = prompt("Administration hours (24h format, e.g. 08:00, 20:00):") || "";
            const startDate = prompt("Start date (YYYY-MM-DD):", new Date().toISOString().slice(0, 10)) || "";
            const endDate = prompt("End date (YYYY-MM-DD):") || "";
            const refillRequired = confirm("Is a prescription refill required?");
            const refillDate = refillRequired ? (prompt("Refill date (YYYY-MM-DD):") || "") : "";
            const checkupRequired = confirm("Is a follow-up consultation required?");
            const checkupDate = checkupRequired ? (prompt("Check-up date (YYYY-MM-DD):") || "") : "";
            const followupType = refillRequired && checkupRequired ? "Refill + Check-up" : (refillRequired ? "Refill" : (checkupRequired ? "Check-up" : ""));
            const followupNotes = (refillRequired || checkupRequired) ? (prompt("Follow-up clinical notes:") || "") : "";
            const doctorId = document.getElementById("doctorId").value;

            try {
                await api("prescription_create", "POST", {
                    queue_id: currentConsultationQueueId,
                    doctor_id: doctorId,
                    medication: medication.trim(),
                    instructions: instructions.trim(),
                    dose_amount: doseAmount.trim(),
                    dose_unit: doseUnit.trim(),
                    quantity: quantity.trim(),
                    quantity_unit: quantityUnit.trim(),
                    times_per_day: timesPerDay.trim(),
                    duration_value: durationValue.trim(),
                    duration_unit: durationUnit.trim(),
                    medication_times: medicationTimes.trim(),
                    start_date: startDate.trim(),
                    end_date: endDate.trim(),
                    refill_required: refillRequired,
                    refill_date: refillDate.trim(),
                    checkup_required: checkupRequired,
                    checkup_date: checkupDate.trim(),
                    followup_type: followupType,
                    followup_notes: followupNotes.trim()
                });
                alert("Prescription successfully registered.");
            } catch (error) {
                alert(error.message);
            }
        }

        async function completeConsultation() {
            if (!currentConsultationQueueId) return;
            const notes = document.getElementById("consultationNotes").value;

            try {
                await api("queue_complete", "POST", {
                    queue_id: currentConsultationQueueId,
                    notes: notes
                });

                document.getElementById("doctorStatus").innerHTML = `<div class="success">Consultation completed successfully.</div>`;
                document.getElementById("consultationControls").innerHTML = "";
                currentConsultationQueueId = null;
                loadQueue();
            } catch (error) {
                alert(error.message);
            }
        }

        async function referPatient() {
            if (!currentConsultationQueueId) return;
            const notes = document.getElementById("consultationNotes").value;

            if (!notes.trim()) {
                alert("Please record the clinical justification for hospital referral in the consultation notes.");
                return;
            }

            if (!confirm("Refer this patient to regional secondary/tertiary hospital care?")) return;

            try {
                await api("queue_refer", "POST", {
                    queue_id: currentConsultationQueueId,
                    notes: notes
                });

                let referralHtml = `<div class="success">Patient transfer referral logged.</div>`;

                try {
                    const referralResult = await api("doctor_referral_hospitals&queue_id=" + encodeURIComponent(currentConsultationQueueId));

                    if (referralResult.gps_available && referralResult.hospitals.length) {
                        referralHtml += `
                            <div class="recordCard">
                                <strong>Recommended Regional Referral Facilities</strong>
                                ${referralResult.hospitals.map((h, i) => `
                                    <span>
                                        ${i + 1}. ${escapeHtml(h.name)} &bull; ${escapeHtml(h.distance_km)} km away &bull; 
                                        <a href="${escapeHtml(h.directions_url)}" target="_blank" rel="noopener">Directions</a>
                                    </span>
                                `).join("")}
                            </div>`;
                    }
                } catch (hErr) {}

                document.getElementById("doctorStatus").innerHTML = referralHtml;
                document.getElementById("consultationControls").innerHTML = "";
                currentConsultationQueueId = null;
                loadQueue();
            } catch (error) {
                alert(error.message);
            }
        }

        window.addEventListener("DOMContentLoaded", function () {
            loadDoctorHospitals();
            setInterval(loadQueue, 3000);
            setInterval(loadStats, 10000);
        });
    </script>

</body>
</html>
