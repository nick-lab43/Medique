"""
MediQueue SA - Wait Time Prediction Model
Trains a regression model that predicts estimated_wait_time (minutes)
from queue state at the moment a patient joins the queue.

Input data: nick_wait_time_training_data.csv (synthetic, see README.md)
Output: wait_model.joblib (trained pipeline, ready to load in api.py)
"""

import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.compose import ColumnTransformer
from sklearn.preprocessing import OneHotEncoder
from sklearn.pipeline import Pipeline
from sklearn.metrics import mean_absolute_error, r2_score
import joblib

DATA_PATH = "nick_wait_time_training_data.csv"
MODEL_PATH = "wait_model.joblib"

CATEGORICAL_FEATURES = ["hospital", "day_of_week", "patient_urgency"]
NUMERIC_FEATURES = [
    "hour_of_day",
    "patients_ahead_total",
    "patients_ahead_same_or_higher_urgency",
    "doctors_on_duty",
    "doctor_avg_consultation_minutes",
]
TARGET = "actual_wait_minutes"


def main():
    df = pd.read_csv(DATA_PATH)

    X = df[CATEGORICAL_FEATURES + NUMERIC_FEATURES]
    y = df[TARGET]

    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42
    )

    preprocessor = ColumnTransformer(
        transformers=[
            ("cat", OneHotEncoder(handle_unknown="ignore"), CATEGORICAL_FEATURES),
        ],
        remainder="passthrough",  # numeric features pass through unchanged
    )

    model = Pipeline(steps=[
        ("preprocess", preprocessor),
        ("regressor", RandomForestRegressor(
            n_estimators=200, max_depth=8, random_state=42
        )),
    ])

    model.fit(X_train, y_train)

    preds = model.predict(X_test)
    mae = mean_absolute_error(y_test, preds)
    r2 = r2_score(y_test, preds)

    print(f"Test MAE: {mae:.1f} minutes")
    print(f"Test R^2: {r2:.3f}")

    joblib.dump(model, MODEL_PATH)
    print(f"Model saved to {MODEL_PATH}")


if __name__ == "__main__":
    main()
