# Weight Based Shipping - Dhaka & Outside (with Sundarban Courier)

A custom WooCommerce shipping plugin for Bangladesh that automatically calculates shipping costs based on product weight and customer district. Supports **Inside Dhaka**, **Outside Dhaka**, and **Sundarban Courier** with individual weight brackets and editable rates.

---

## 🚀 Features

- Automatically selects the correct shipping method based on customer's **billing district**
- Separate weight-based pricing for:
  - Inside Dhaka
  - Outside Dhaka
  - Sundarban Courier (selected districts)
- Fully customizable rate brackets in WooCommerce settings
- Supports Bangla and English district names
- Easy to configure and extend

---

## 📦 Shipping Zones & Rates

### ✅ Inside Dhaka (Default)

| Weight Range | Cost (৳) |
|--------------|----------|
| 0 - 0.5 kg   | 80       |
| 0.5 - 1 kg   | 100      |
| ...          | ...      |
| 45 - 50 kg   | 1000     |

### ✅ Outside Dhaka (Default)

| Weight Range | Cost (৳) |
|--------------|----------|
| 0 - 0.5 kg   | 120      |
| 0.5 - 1 kg   | 140      |
| ...          | ...      |
| 45 - 50 kg   | 1200     |

### ✅ Sundarban Courier (Selected Districts)

| Weight Range | Cost (৳) |
|--------------|----------|
| 0 - 5 kg     | 150      |
| 5 - 10 kg    | 180      |
| 10 - 15 kg   | 200      |
| 15 - 20 kg   | 250      |
| 20 - 25 kg   | 300      |
| 25 - 30 kg   | 400      |
| 30 - 40 kg   | 500      |

> Sundarban Courier is enabled only for selected districts like `Khulna`, `Barisal`, etc.

---

## ⚙️ Installation

1. Download the plugin `.zip` file
2. Upload it to your WordPress site:
   - Go to **Plugins → Add New → Upload Plugin**
3. Activate the plugin
4. Go to **WooCommerce → Settings → Shipping** and configure rates for each method:
   - Inside Dhaka
   - Outside Dhaka
   - Sundarban Courier

---

## 📐 How It Works

- The plugin checks the **billing state/district** at checkout.
- Based on the district name:
  - Shows **Inside Dhaka** if `Dhaka` or `ঢাকা`
  - Shows **Sundarban Courier** for districts like `Khulna`, `Barisal`
  - Otherwise shows **Outside Dhaka**
- Only **one method is visible** to the customer, making checkout simple.

---

## 🛠 Developers

your-plugin-folder/
├── assets/
│   └── js/
│       └── auto-select-shipping.js
├── includes/
│   ├── class-wc-shipping-inside-dhaka.php
│   ├── class-wc-shipping-outside-dhaka.php
│   └── class-wc-shipping-sundarban-courier.php
└── your-main-plugin-file.php
