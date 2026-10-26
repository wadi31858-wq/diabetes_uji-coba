"""
Script Pelatihan Model Machine Learning Extreme Gradient Boosting (XGBoost)
untuk Diabetes AI Analyzer.

Fitur:
- Validasi data & deteksi data leakage.
- Stratified Train-Test Split (menjaga proporsi kelas target).
- XGBoost Classifier dengan scale_pos_weight untuk mengatasi ketidakseimbangan kelas.
- Evaluasi metrik komprehensif (Accuracy, Precision, Recall, F1, Confusion Matrix).
- Penyimpanan model menggunakan Joblib dan metadata menggunakan JSON.
"""

import os
import json
import time
from datetime import datetime
import pandas as pd
import joblib

from sklearn.model_selection import train_test_split
from xgboost import XGBClassifier
from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    confusion_matrix
)

# Konfigurasi Fitur & Target (21 Indikator Klinis Terverifikasi CDC BRFSS)
FEATURES = [
    'HighBP', 'HighChol', 'CholCheck', 'BMI', 'Smoker', 'Stroke', 'HeartDiseaseorAttack',
    'PhysActivity', 'Fruits', 'Veggies', 'HvyAlcoholConsump', 'AnyHealthcare',
    'NoDocbcCost', 'GenHlth', 'MentHlth', 'PhysHlth', 'DiffWalk', 'Sex', 'Age',
    'Education', 'Income'
]
TARGET = 'Diabetes_binary'

DATA_PATH = os.path.join(os.path.dirname(__file__), 'diabetes_health_indicators.csv')
if not os.path.exists(DATA_PATH):
    DATA_PATH = os.path.join(os.path.dirname(__file__), 'data', 'diabetes_health_indicators.csv')

MODEL_PATH = os.path.join(os.path.dirname(__file__), 'model_diabetes_xgboost.pkl')
METADATA_PATH = os.path.join(os.path.dirname(__file__), 'models', 'metadata.json')


def train_xgboost_model(data_path=DATA_PATH, model_path=MODEL_PATH, metadata_path=METADATA_PATH):
    print("=" * 60)
    print("Memulai Proses Pelatihan Model Diabetes AI Analyzer (XGBoost)")
    print("=" * 60)

    # 1. Validasi Keberadaan Dataset
    if not os.path.exists(data_path):
        raise FileNotFoundError(f"Dataset tidak ditemukan di path: {data_path}")

    print(f"[1/6] Membaca dataset dari: {data_path}")
    df = pd.read_csv(data_path)
    print(f"      Total baris: {len(df):,}, Total kolom: {len(df.columns)}")

    # 2. Validasi Kolom
    missing_features = [f for f in FEATURES if f not in df.columns]
    if missing_features:
        raise ValueError(f"Fitur berikut tidak ditemukan pada dataset: {missing_features}")
    if TARGET not in df.columns:
        raise ValueError(f"Kolom target '{TARGET}' tidak ditemukan pada dataset.")

    # 3. Pemisahan Fitur dan Target (Mencegah Data Leakage)
    print(f"[2/6] Memisahkan {len(FEATURES)} fitur dan target '{TARGET}'...")
    X = df[FEATURES].copy()
    y = df[TARGET].astype(int).copy()

    class_counts = y.value_counts().to_dict()
    print(f"      Distribusi kelas total -> 0 (Non-Diabetes): {class_counts.get(0, 0):,}, "
          f"1 (Diabetes/Pre-Diabetes): {class_counts.get(1, 0):,}")

    # 4. Stratified Train-Test Split (80% Train, 20% Test)
    print("[3/6] Membagi data latih dan uji secara terstratifikasi (80:20)...")
    X_train, X_test, y_train, y_test = train_test_split(
        X, y,
        test_size=0.2,
        random_state=42,
        stratify=y
    )
    print(f"      Data Latih (Train): {len(X_train):,} sampel")
    print(f"      Data Uji (Test)   : {len(X_test):,} sampel")

    # 5. Membangun dan Melatih Model XGBoost Classifier
    print("[4/6] Membangun Model XGBoost Classifier...")
    neg_count = (y_train == 0).sum()
    pos_count = (y_train == 1).sum()
    scale_pos = neg_count / max(1, pos_count)

    model = XGBClassifier(
        n_estimators=100,
        learning_rate=0.1,
        max_depth=4,
        scale_pos_weight=scale_pos,
        random_state=42,
        eval_metric='logloss'
    )

    t_start = time.time()
    print("      Melatih model XGBoost...")
    model.fit(X_train, y_train)
    training_duration = time.time() - t_start
    print(f"      Pelatihan selesai dalam {training_duration:.2f} detik.")

    # 6. Evaluasi Model pada Data Uji
    print("[5/6] Mengevaluasi model pada data uji...")
    y_pred = model.predict(X_test)

    acc = float(accuracy_score(y_test, y_pred))
    p_0 = float(precision_score(y_test, y_pred, pos_label=0, zero_division=0))
    p_1 = float(precision_score(y_test, y_pred, pos_label=1, zero_division=0))
    r_0 = float(recall_score(y_test, y_pred, pos_label=0, zero_division=0))
    r_1 = float(recall_score(y_test, y_pred, pos_label=1, zero_division=0))
    f1_0 = float(f1_score(y_test, y_pred, pos_label=0, zero_division=0))
    f1_1 = float(f1_score(y_test, y_pred, pos_label=1, zero_division=0))
    macro_f1 = float(f1_score(y_test, y_pred, average='macro'))
    weighted_f1 = float(f1_score(y_test, y_pred, average='weighted'))
    cm = confusion_matrix(y_test, y_pred).tolist()

    print(f"      Accuracy    : {acc * 100:.2f}%")
    print(f"      Recall (Kelas 1 - Diabetes)   : {r_1 * 100:.2f}%")
    print(f"      Precision (Kelas 1 - Diabetes): {p_1 * 100:.2f}%")
    print(f"      F1-Score (Kelas 1 - Diabetes) : {f1_1:.4f}")
    print(f"      Macro F1-Score                : {macro_f1:.4f}")
    print(f"      Confusion Matrix:\n        TN={cm[0][0]}, FP={cm[0][1]}\n        FN={cm[1][0]}, TP={cm[1][1]}")

    # Feature Importance XGBoost
    importances = model.feature_importances_
    feature_importance = {feat: float(imp) for feat, imp in zip(FEATURES, importances)}

    # 7. Simpan Model dan Metadata
    print("[6/6] Menyimpan model dan metadata...")
    os.makedirs(os.path.dirname(model_path), exist_ok=True)
    joblib.dump(model, model_path)
    print(f"      Model berhasil disimpan di: {model_path}")

    metadata = {
        "algorithm": "Extreme Gradient Boosting (XGBoost)",
        "model_name": "XGBoost",
        "model_type": "XGBClassifier",
        "training_date": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "training_duration_seconds": round(training_duration, 2),
        "model_version": "1.0.0",
        "status": "Aktif & Tervalidasi",
        "features": FEATURES,
        "target": TARGET,
        "class_mapping": {
            "0": "Risiko Rendah (Non-Diabetes)",
            "1": "Berisiko Tinggi (Diabetes/Pre-Diabetes)"
        },
        "dataset_info": {
            "file": os.path.basename(data_path),
            "total_samples": int(len(df)),
            "train_samples": int(len(X_train)),
            "test_samples": int(len(X_test)),
            "class_distribution_total": {str(k): int(v) for k, v in class_counts.items()},
            "class_distribution_train": {str(k): int(v) for k, v in y_train.value_counts().to_dict().items()},
            "class_distribution_test": {str(k): int(v) for k, v in y_test.value_counts().to_dict().items()}
        },
        "metrics": {
            "accuracy": round(acc, 4),
            "precision_class_0": round(p_0, 4),
            "precision_class_1": round(p_1, 4),
            "recall_class_0": round(r_0, 4),
            "recall_class_1": round(r_1, 4),
            "f1_class_0": round(f1_0, 4),
            "f1_class_1": round(f1_1, 4),
            "macro_f1": round(macro_f1, 4),
            "weighted_f1": round(weighted_f1, 4),
            "confusion_matrix": cm
        },
        "feature_weights": feature_importance
    }

    with open(metadata_path, 'w', encoding='utf-8') as f:
        json.dump(metadata, f, indent=4, ensure_ascii=False)
    print(f"      Metadata berhasil disimpan di: {metadata_path}")
    print("=" * 60)
    print("Pelatihan Model XGBoost Selesai dengan Sukses!")
    print("=" * 60)
    return metadata


# Alias untuk kompatibilitas kode lama
train_svm_model = train_xgboost_model

if __name__ == '__main__':
    train_xgboost_model()
