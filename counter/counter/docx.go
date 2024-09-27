package counter

import (
	"archive/zip"
	"encoding/xml"
	"fmt"
	"io"
	"strings"
)

type DOCX struct{}

func (d DOCX) Words(path string) (int, error) {
	reader, err := zip.OpenReader(path)
	if err != nil {
		return 0, err
	}
	defer func(reader *zip.ReadCloser) {
		err := reader.Close()
		if err != nil {
		}
	}(reader)

	var doc io.ReadCloser
	for _, file := range reader.File {
		if file.Name == "word/document.xml" {
			doc, err = file.Open()
			if err != nil {
				return 0, err
			}
			break
		}
	}
	if doc == nil {
		return 0, fmt.Errorf("document.xml not fount in docx")
	}
	defer func(doc io.ReadCloser) {
		err := doc.Close()
		if err != nil {

		}
	}(doc)

	decoder := xml.NewDecoder(doc)
	count := 0
	for {
		token, err := decoder.Token()
		if err == io.EOF {
			break
		}
		if err != nil {
			return 0, err
		}

		switch element := token.(type) {
		case xml.StartElement:
			if element.Name.Local == "t" { // text nodes are in <w:t> tags
				var content string
				if err := decoder.DecodeElement(&content, &element); err != nil {
					return 0, err
				}
				count += len(strings.Fields(content))
			}
		}
	}

	return count, nil
}

func (d DOCX) Characters(path string) (int, error) {
	return 0, nil
}
