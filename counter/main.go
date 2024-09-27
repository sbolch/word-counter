package main

import (
	"log"
	"os"
	"path/filepath"
	"strings"
	"word-counter/converter"
	"word-counter/counter"
)

func main() {
	args := os.Args

	log.SetFlags(0)

	if len(args) < 2 {
		log.Fatal("No file to count in")
	}

	file := args[1]
	ext := strings.ToLower(filepath.Ext(file))

	var c counter.Counter
	switch ext {
	case ".pdf":
		c = counter.PDF{}
	case ".docx":
		c = counter.DOCX{}
	case ".doc":
		c = counter.DOCX{}
		conv := converter.DOC{}
		tmp, err := conv.Convert(file)
		if err != nil {
			log.Fatalf("Error: %v\n", err)
		}
		defer func(path string) {
			err := os.RemoveAll(path)
			if err != nil {
			}
		}(filepath.Dir(tmp))
		file = tmp
	case ".odt":
		c = counter.ODT{}
	case ".rtf":
		c = counter.RTF{}
	case ".xlsx":
		c = counter.XLSX{}
	case ".xls":
		c = counter.XLSX{}
		conv := converter.XLS{}
		tmp, err := conv.Convert(file)
		if err != nil {
			log.Fatalf("Error: %v\n", err)
		}
		defer func(path string) {
			err := os.RemoveAll(path)
			if err != nil {
			}
		}(filepath.Dir(tmp))
		file = tmp
	case ".ods":
		c = counter.ODS{}
	case ".csv":
		c = counter.CSV{}
	default:
		c = counter.TXT{}
	}

	count, err := c.Words(file)
	if err != nil {
		log.Fatalf("Error: %v\n", err)
	}

	log.Printf("%d\n", count)
}
