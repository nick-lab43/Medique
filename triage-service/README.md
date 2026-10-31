# MediQueue SA Symptom Triage Service

## Purpose

This service receives a patient's symptom description and returns an urgency classification: `Low`, `Moderate`, or `High`. It is a decision-support feature for patient prioritisation and does not provide a medical diagnosis.

## Implementation

- Python and Flask expose the HTTP API.
- TF-IDF converts symptom text into numerical features.
- Logistic Regression predicts the urgency category.
- Validation rejects clearly unrelated or nonsensical input and asks for more detail when the description is too vague.
- A conservative safety-rule layer marks a small set of critical symptom phrases as High urgency for professional review.
- Low-confidence results are returned as Moderate with `needs_review: true`.

The model uses a synthetic dataset of 1,200 labelled symptom descriptions (400 per urgency class). These examples are for prototype development, not clinical validation.

## Run on Windows

Open PowerShell in this folder and run:

```powershell
py -m venv .venv
.\.venv\Scripts\Activate.ps1
py -m pip install -r requirements.txt
py test_model.py
py test_validation.py
py test_service.py
py app.py
```

The service runs on `http://127.0.0.1:5002`.

Health check:

```powershell
curl.exe "http://127.0.0.1:5002/health"
```

## Prediction API

`POST http://localhost:5002/predict_urgency`

Request:

```json
{"symptoms":"high fever for two days"}
```

Successful response:

```json
{
  "urgency": "Moderate",
  "confidence": 0.81,
  "needs_review": false,
  "safety_override": false,
  "disclaimer": "For triage support only; this result is not a medical diagnosis."
}
```

Clearly invalid or unrelated symptom descriptions return HTTP `422` with the code `invalid_symptom_description`.

## Integration with the main application

The main PHP backend is in the parent folder (`../api.php`). Its triage route calls this service at `http://localhost:5002/predict_urgency`. If the service explicitly rejects an invalid symptom description, the API returns a validation error rather than saving an urgency for that request. If the service is unavailable, the existing keyword-based triage rules are used as a fallback. The wait-time prediction service on port `5001` is separate and unchanged.

## Included files

- `app.py` — Flask API endpoint and prediction logic
- `triage_model.joblib` — trained TF-IDF and Logistic Regression pipeline
- `triage_training_data.csv` — labelled training dataset
- `build_training_dataset.py` — builds the development dataset
- `train_model.py` — trains and saves the classifier
- `validation.py` — input checks
- `safety_rules.py` — critical symptom wording checks
- `test_model.py`, `test_validation.py`, `test_service.py` — automated checks
- `stress_test.py` — mixed input cases
- `metrics.json` — model evaluation summary
- `requirements.txt` — Python dependencies
- `run.txt` — short Windows run instructions
