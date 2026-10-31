"""
MediQueue SA - Wait Time Prediction Service
Small HTTP service that api.php calls whenever it needs an
estimated_wait_time for a patient in the queue.

Run:
    python3 app.py
Listens on http://localhost:5001

Endpoint:
    POST /predict_wait
    Body (JSON):
        {
          "hospital": "Groote Schuur",
          "day_of_week": "Tue",
          "hour_of_day": 14,
          "patient_urgency": "Moderate",
          "patients_ahead_total": 12,
          "patients_ahead_same_or_higher_urgency": 4,
          "doctors_on_duty": 2,
          "doctor_avg_consultation_minutes": 15.0
        }
    Response (JSON):
        { "estimated_wait_minutes": 37.2 }
"""

from flask import Flask, request, jsonify
import joblib
import pandas as pd

app = Flask(__name__)
model = joblib.load("wait_model.joblib")

REQUIRED_FIELDS = [
    "hospital",
    "day_of_week",
    "hour_of_day",
    "patient_urgency",
    "patients_ahead_total",
    "patients_ahead_same_or_higher_urgency",
    "doctors_on_duty",
    "doctor_avg_consultation_minutes",
]


@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"})


@app.route("/predict_wait", methods=["POST"])
def predict_wait():
    data = request.get_json(silent=True)
    if data is None:
        return jsonify({"error": "Request body must be JSON"}), 400

    missing = [f for f in REQUIRED_FIELDS if f not in data]
    if missing:
        return jsonify({"error": f"Missing fields: {missing}"}), 400

    try:
        row = pd.DataFrame([{f: data[f] for f in REQUIRED_FIELDS}])
        prediction = float(model.predict(row)[0])
        prediction = max(1.0, round(prediction, 1))  # never predict below 1 minute
    except Exception as e:
        return jsonify({"error": f"Prediction failed: {str(e)}"}), 500

    return jsonify({"estimated_wait_minutes": prediction})


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=False)
