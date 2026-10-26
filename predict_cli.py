#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
CLI Predictor for Diabetes AI Analyzer (XGBoost)
Provides CLI & JSON interface for Laravel integration.
"""

import sys
import json
import os
import joblib
import pandas as pd
import numpy as np

# Definisi urutan 21 fitur model CDC XGBoost
FEATURES = [
    'HighBP', 'HighChol', 'CholCheck', 'BMI', 'Smoker',
    'Stroke', 'HeartDiseaseorAttack', 'PhysActivity', 'Fruits', 'Veggies',
    'HvyAlcoholConsump', 'AnyHealthcare', 'NoDocbcCost', 'GenHlth',
    'MentHlth', 'PhysHlth', 'DiffWalk', 'Sex', 'Age', 'Education', 'Income'
]

def map_age_to_cdc(age):
    """Konversi usia riil ke kategori usia CDC BRFSS (1-13)."""
    if age < 25: return 1
    elif age < 30: return 2
    elif age < 35: return 3
    elif age < 40: return 4
    elif age < 45: return 5
    elif age < 50: return 6
    elif age < 55: return 7
    elif age < 60: return 8
    elif age < 65: return 9
    elif age < 70: return 10
    elif age < 75: return 11
    elif age < 80: return 12
    else: return 13

def calculate_bmi(height_cm, weight_kg):
    if height_cm <= 0 or weight_kg <= 0:
        return 22.5
    h_m = height_cm / 100.0
    return round(weight_kg / (h_m * h_m), 1)

def main():
    data = {}
    if len(sys.argv) > 1:
        try:
            data = json.loads(sys.argv[1])
        except Exception:
            pass
    if not data:
        try:
            stdin_data = sys.stdin.read().strip()
            if stdin_data:
                data = json.loads(stdin_data)
        except Exception:
            pass

    # Ekstraksi parameter
    gender = str(data.get('gender', 'Laki-laki')).strip()
    sex_val = 1 if gender.lower() in ['laki-laki', 'pria', 'male', '1'] else 0
    age = int(data.get('age', 30))
    height = float(data.get('height', 170))
    weight = float(data.get('weight', 65))
    
    bmi = float(data.get('bmi', calculate_bmi(height, weight)))
    high_bp = int(data.get('high_bp', data.get('HighBP', 0)))
    high_chol = int(data.get('high_chol', data.get('HighChol', 0)))
    phys_act = int(data.get('phys_activity', data.get('PhysActivity', 1)))
    family_hist = int(data.get('family_history', 0))
    heart_disease = int(data.get('heart_disease', data.get('HeartDiseaseorAttack', 0)))
    
    # Skoring Klinis (Algoritma Kuesioner KlikDokter)
    score = 0
    if sex_val == 1: score += 1
    if age < 35: score += 0
    elif age < 50: score += 3
    elif age < 65: score += 5
    else: score += 7

    if bmi >= 25.0: score += 4
    if bmi >= 30.0: score += 2

    if high_bp == 1: score += 5
    if family_hist == 1 or heart_disease == 1: score += 5
    if phys_act == 0: score += 3
    if high_chol == 1: score += 2

    score = min(score, 25)

    # Evaluasi Machine Learning XGBoost
    svm_pred = 0
    try:
        model_path = os.path.join(os.path.dirname(__file__), 'model_diabetes_xgboost.pkl')
        if not os.path.exists(model_path):
            model_path = os.path.join(os.path.dirname(__file__), 'models', 'diabetes_model.joblib')
        if os.path.exists(model_path):
            model = joblib.load(model_path)
            
            # Buat DataFrame 21 fitur lengkap
            input_features = {
                'HighBP': high_bp,
                'HighChol': high_chol,
                'CholCheck': int(data.get('CholCheck', 1)),
                'BMI': bmi,
                'Smoker': int(data.get('Smoker', 0)),
                'Stroke': int(data.get('Stroke', 0)),
                'HeartDiseaseorAttack': heart_disease,
                'PhysActivity': phys_act,
                'Fruits': int(data.get('Fruits', 1)),
                'Veggies': int(data.get('Veggies', 1)),
                'HvyAlcoholConsump': int(data.get('HvyAlcoholConsump', 0)),
                'AnyHealthcare': int(data.get('AnyHealthcare', 1)),
                'NoDocbcCost': int(data.get('NoDocbcCost', 0)),
                'GenHlth': int(data.get('GenHlth', 2 if score <= 6 else (3 if score <= 14 else 4))),
                'MentHlth': int(data.get('MentHlth', 0)),
                'PhysHlth': int(data.get('PhysHlth', 0)),
                'DiffWalk': int(data.get('DiffWalk', 0)),
                'Sex': sex_val,
                'Age': map_age_to_cdc(age),
                'Education': int(data.get('Education', 5)),
                'Income': int(data.get('Income', 6))
            }
            input_df = pd.DataFrame([input_features])[FEATURES]
            svm_pred = int(model.predict(input_df)[0])
    except Exception as ex:
        svm_pred = 1 if score > 14 else 0

    # Tentukan Kategori & Rekomendasi (Sesuai Desain Resmi KlikDokter)
    if score <= 6 and svm_pred == 0:
        category = 'Rendah'
        color = '#6ebd20'
        pointer_left = '16.7%'
        recommendations = [
            'Pertahankan pola makan bergizi seimbang dan batasi konsumsi gula berlebih.',
            'Lakukan aktivitas fisik atau olahraga teratur minimal 30 menit setiap hari.',
            'Jaga berat badan ideal dan pantau tekanan darah secara berkala.',
            'Lakukan pemeriksaan gula darah rutin setidaknya 1 tahun sekali.'
        ]
    elif score <= 14:
        category = 'Sedang'
        color = '#f37920'
        pointer_left = '50%'
        recommendations = [
            'Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.',
            'Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.',
            'Konsumsi makan makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.',
            'Konsultasi dengan dokter spesialis penyakit dalam.'
        ]
    else:
        category = 'Tinggi'
        color = '#ff4252'
        pointer_left = '83.3%'
        recommendations = [
            'Segera lakukan konsultasi dengan dokter spesialis penyakit dalam untuk evaluasi lebih lanjut.',
            'Lakukan pemeriksaan laboratorium mendalam (seperti HbA1c dan Gula Darah Puasa).',
            'Batasi secara ketat konsumsi gula tambahan, karbohidrat olahan, dan minuman manis.',
            'Terapkan pola hidup aktif terencana dan pantau kadar glukosa mandiri secara berkala.'
        ]

    # Kategori BMI
    if bmi < 18.5: bmi_cat = 'Berat Rendah'
    elif bmi < 25.0: bmi_cat = 'Normal'
    elif bmi < 30.0: bmi_cat = 'Kelebihan Berat Badan'
    else: bmi_cat = 'Obesitas'

    response = {
        'status': 'success',
        'gender': gender,
        'age': age,
        'bmi': bmi,
        'bmi_category': bmi_cat,
        'score': score,
        'svm_prediction': svm_pred,
        'category': category,
        'color': color,
        'pointer_left': pointer_left,
        'recommendations': recommendations
    }

    print(json.dumps(response))

if __name__ == '__main__':
    main()
