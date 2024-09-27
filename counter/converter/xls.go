package converter

import (
	"os"
	"os/exec"
	"path/filepath"
	"strings"
)

type XLS struct{}

func (d XLS) Convert(path string) (string, error) {
	tmpDir, err := os.MkdirTemp("", "word-counter_")
	if err != nil {
		return "", err
	}

	cmd := exec.Command(
		"libreoffice",
		"--headless",
		"--convert-to",
		"xlsx",
		path,
		"--outdir",
		tmpDir,
	)
	_, err = cmd.Output()
	if err != nil {
		defer func(path string) {
			err := os.RemoveAll(path)
			if err != nil {
			}
		}(tmpDir)
		return "", err
	}

	//time.Sleep(2 * time.Second)

	tmpFile := filepath.Join(tmpDir, strings.Replace(filepath.Base(path), "xls", "xlsx", 1))
	if _, err := os.Stat(tmpFile); os.IsNotExist(err) {
		defer func(path string) {
			err := os.RemoveAll(path)
			if err != nil {
			}
		}(tmpDir)
		return "", err
	}

	return tmpFile, nil
}
