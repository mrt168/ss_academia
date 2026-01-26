# ss_academia_with_design

StockSun Academia LP - EC事業者向けSNS運用サービス

## プロジェクト情報

- **技術スタック**: HTML + SCSS + JS
- **開発コマンド**: `npm run dev`
- **ビルド**: `npm run build`

## ファイル構成

- `figma/` - Figmaデザイン・画像
- `_docs/design/` - デザイン仕様・スクリーンショット
- `screenshot/` - 比較用スクリーンショット
- `src/scss/` - スタイルシート

---

## Figma Design Alignment Workflow

Figmaデザインと実装を揃えるための反復修正ワークフロー。

### 作業手順

1. **Figmaデザイン画像を確認** - `figma/images/` または `_docs/design/`
2. **スクリーンショット撮影** - Playwrightまたはブラウザで撮影
3. **オーバーレイ比較画像作成** - ImageMagickで半透明合成
4. **修正を実施** - HTML/SCSS を編集
5. **⚠️ 3サイクルごとに構成確認**

### オーバーレイ比較の作成方法

```bash
# ImageMagickでオーバーレイ比較
# 1. Figmaを実装と同サイズにリサイズ
convert figma_design.png -resize 1440x900 figma_resized.png

# 2. 特定エリア（FV等）を切り出し
convert figma_resized.png -crop 1440x450+0+0 figma_fv.png
convert screenshot.png -crop 1440x450+0+0 impl_fv.png

# 3. オーバーレイ（50%透明で重ねる）
composite -dissolve 50% -gravity NorthWest figma_fv.png impl_fv.png overlay.png
```

### ⚠️ 3サイクル構成確認（重要）

**3回の修正イテレーションごとに必ず確認:**

□ レイアウト構造は正しいか？
  - 左右の配置（何が左で何が右か）
  - 上下の順序（要素の並び順）

□ 主要要素の配置
  - タイトル/見出しの位置
  - 画像の位置
  - ボタンの位置
  - カード/パネルの配置

□ flex/gridの方向
  - row vs column
  - wrap の有無

□ 根本的なHTML構造
  - 要素の親子関係
  - セクションの分け方

**構成が間違っている場合**: 細かいサイズ調整より先にHTML構造を修正する

### よくある問題

| 症状 | 原因 | 修正方法 |
|------|------|----------|
| 左右が逆 | HTML要素の順序/flex-direction | HTML構造を修正 |
| サイズが全然違う | スケールが違う | Figmaをリサイズして再比較 |
| 修正しても直らない | 構成確認していない | 構成チェックリストを確認 |

---

## このプロジェクトでの作業例

```bash
# 1. CSSビルド
npm run build

# 2. スクリーンショット撮影（FV部分）
npx playwright screenshot file://$PWD/index.html screenshot/fv.png --viewport-size=1440,900

# 3. オーバーレイ比較
composite -dissolve 50% -gravity NorthWest \
  <(convert _docs/design/PC.png -resize 1440x900 -crop 1440x450+0+0 +repage png:-) \
  <(convert screenshot/fv.png -crop 1440x450+0+0 +repage png:-) \
  screenshot/overlay_fv.png
```
