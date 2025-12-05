# Diagrams Folder

This folder should contain PNG/SVG versions of all Mermaid diagrams used in the LaTeX report.

## Instructions to Generate Diagrams

### Using Mermaid Live Editor (Easiest)
1. Go to https://mermaid.live
2. Copy each diagram code from `../MERMAID_DIAGRAMS.md`
3. Paste into the editor
4. Export as PNG (high quality)
5. Save with the naming convention below

### Using Mermaid CLI
```bash
npm install -g @mermaid-js/mermaid-cli
mmdc -i diagram.mmd -o diagram.png -w 1200 -H 800
```

## File Naming Convention

Save all diagrams with these exact names:

| Filename | Diagram | Source |
|----------|---------|--------|
| `01_usecases.png` | Use case diagram | MERMAID_DIAGRAMS.md (Diagram 1) |
| `02_loan_sequence.png` | Loan process sequence | MERMAID_DIAGRAMS.md (Diagram 2) |
| `03_ecommerce_sequence.png` | E-commerce sequence | MERMAID_DIAGRAMS.md (Diagram 3) |
| `04_loan_states.png` | Loan status state machine | MERMAID_DIAGRAMS.md (Diagram 4) |
| `05_order_states.png` | Order status state machine | MERMAID_DIAGRAMS.md (Diagram 5) |
| `06_stock_flow.png` | Double stock system flow | MERMAID_DIAGRAMS.md (Diagram 6) |
| `07_architecture.png` | System architecture | MERMAID_DIAGRAMS.md (Diagram 7) |
| `08_erd.png` | Entity relationship diagram | MERMAID_DIAGRAMS.md (Diagram 8) |
| `09_user_workflow.png` | Complete user workflow | MERMAID_DIAGRAMS.md (Diagram 9) |
| `10_calendar.png` | Calendar availability view | MERMAID_DIAGRAMS.md (Diagram 10) |
| `11_admin_dashboard.png` | Admin dashboard structure | MERMAID_DIAGRAMS.md (Diagram 11) |
| `12_installation.png` | Installation flow | MERMAID_DIAGRAMS.md (Diagram 12) |
| `13_payment_flow.png` | Payment processing flow | MERMAID_DIAGRAMS.md (Diagram 13) |
| `14_migration.png` | Stock migration strategy | MERMAID_DIAGRAMS.md (Diagram 14) |
| `15_security.png` | Security layers | MERMAID_DIAGRAMS.md (Diagram 15) |

## Quick Generation Script

Save as `generate_diagrams.sh`:

```bash
#!/bin/bash

# Array of diagram numbers and names
diagrams=(
    "01_usecases"
    "02_loan_sequence"
    "03_ecommerce_sequence"
    "04_loan_states"
    "05_order_states"
    "06_stock_flow"
    "07_architecture"
    "08_erd"
    "09_user_workflow"
    "10_calendar"
    "11_admin_dashboard"
    "12_installation"
    "13_payment_flow"
    "14_migration"
    "15_security"
)

echo "Converting Mermaid diagrams to PNG..."
for diagram in "${diagrams[@]}"; do
    echo "Converting $diagram..."
    mmdc -i "${diagram}.mmd" -o "${diagram}.png" -w 1200 -H 800 -b white
done

echo "✅ All diagrams converted!"
```

Then run:
```bash
chmod +x generate_diagrams.sh
./generate_diagrams.sh
```

## Important Notes

- **Width**: Use 1200px width for better LaTeX rendering
- **Height**: Auto or 800px
- **Background**: White background for LaTeX compatibility
- **Format**: PNG is recommended (higher compatibility with LaTeX)
- **Quality**: High quality (DPI 300 if possible)

## Quick Online Conversion

For each diagram in `../MERMAID_DIAGRAMS.md`:
1. Copy the mermaid code block
2. Paste at https://mermaid.live/edit
3. Use browser "Export" or screenshot
4. Save with naming convention
5. Place in this folder

---

Once all diagrams are generated, the LaTeX report will automatically reference them.
