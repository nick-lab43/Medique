import csv, random
random.seed(7)

hospitals = ["Chris Hani Baragwanath", "Groote Schuur", "King Edward VIII", "Steve Biko Academic", "Tygerberg"]
urgencies = ["Low", "Moderate", "High"]
urgency_factor = {"Low": 1.0, "Moderate": 0.6, "High": 0.2}  # higher urgency jumps the queue

rows = []
for _ in range(500):
    hospital = random.choice(hospitals)
    day = random.choice(["Mon","Tue","Wed","Thu","Fri","Sat","Sun"])
    hour = random.choice(list(range(7,19)))  # clinic hours 7am-7pm
    urgency = random.choices(urgencies, weights=[0.5,0.35,0.15])[0]  # low urgency most common

    doctors_on_duty = random.choice([1,1,2,2,2,3,3,4])
    avg_consult_minutes = round(random.uniform(8,25),1)

    patients_ahead_total = random.randint(0,40)
    # patients ahead who are same-or-higher urgency (these are the ones that actually matter for this patient's wait)
    if urgency == "High":
        ahead_relevant = random.randint(0,3)
    elif urgency == "Moderate":
        ahead_relevant = random.randint(0, min(12, patients_ahead_total))
    else:
        ahead_relevant = patients_ahead_total

    busy_multiplier = 1.25 if hour in (8,9,10,13,14,15) else 1.0

    base_wait = (ahead_relevant / max(doctors_on_duty,1)) * avg_consult_minutes
    wait = base_wait * urgency_factor[urgency] * busy_multiplier
    # noise
    wait += random.gauss(0, wait*0.15 + 2)
    wait = max(2, min(480, wait))

    rows.append([
        hospital, day, hour, urgency,
        patients_ahead_total, ahead_relevant,
        doctors_on_duty, avg_consult_minutes,
        round(wait,1)
    ])

with open("nick_wait_time_training_data.csv", "w", newline="", encoding="utf-8") as f:
    w = csv.writer(f)
    w.writerow([
        "hospital", "day_of_week", "hour_of_day", "patient_urgency",
        "patients_ahead_total", "patients_ahead_same_or_higher_urgency",
        "doctors_on_duty", "doctor_avg_consultation_minutes",
        "actual_wait_minutes"
    ])
    w.writerows(rows)

print(len(rows), "rows written")
