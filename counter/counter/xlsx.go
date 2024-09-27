package counter

import (
	"github.com/tealeg/xlsx"
	"strings"
)

type XLSX struct{}

func (x XLSX) Words(path string) (int, error) {
	doc, err := xlsx.OpenFile(path)
	if err != nil {
		return 0, err
	}

	count := 0
	for _, sheet := range doc.Sheets {
		for _, row := range sheet.Rows {
			for _, cell := range row.Cells {
				count += len(strings.Fields(cell.String()))
			}
		}
	}

	return count, nil
}

func (x XLSX) Characters(path string) (int, error) {
	return 0, nil
}
