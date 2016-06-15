
;; lines below were added by fisher

;; == use system-wide color theme extension
(require 'color-theme)
(color-theme-initialize)
;;(color-theme-blippblopp)  ;; light theme
(color-theme-midnight)    ;; dark theme

;; this is minor mode for current line higlighting
(hl-line-mode)
(set-face-background hl-line-face "gray6")

;; dired to highight current line
(add-hook 'dired-mode-hook (lambda () (hl-line-mode)))

;; == extension for git management
(require 'magit)
(global-set-key (kbd "<f1> <f5>") 'magit-status)

;; == extension for erlang (assuming it is already in 'load-path)
(require 'erlang-start)

(add-hook 'erlang-mode-hook
	  (lambda ()
	    (show-paren-mode 1)
	    (hl-line-mode)
	    (flymake-mode)))

;; == flymake-mode setup
(require 'flymake)

;; helper fun to create temp file in global /tmp
(defun flymake-create-temp-in-system-tempdir (filename prefix)
  (make-temp-file
   (or prefix "flymake")
   nil
   (file-name-nondirectory (directory-file-name filename))))

;; our own redefined erlang-mode with custom flymake script
(defun flymake-erlang-init ()
  (let* ((temp-file
	  (flymake-init-create-temp-buffer-copy
	   'flymake-create-temp-in-system-tempdir))
	 (local-file
	  (file-relative-name temp-file
			      (file-name-directory buffer-file-name))))
    (list "~/bin/flymode-escript" (list local-file))))

;; define known file extensions to use with flymake-mode
(add-to-list 'flymake-allowed-file-name-masks '("\\.hrl$" flymake-erlang-init))
(add-to-list 'flymake-allowed-file-name-masks '("\\.erl$" flymake-erlang-init))

;; set keys to use with our flymake mode
(global-set-key [f5] 'flymake-goto-next-error)
(global-set-key [f6] 'flymake-display-err-menu-for-current-line)

;; == org mode settings
(require 'org-install)
(global-set-key "\C-cl" 'org-store-link)
(global-set-key "\C-ca" 'org-agenda)
(global-set-key "\C-cb" 'org-iswitchb)

(setq org-agenda-files (list "~/prj/todo/dumb-templates.org"
                             "~/prj/todo/checklist-job.org"
                             "~/prj/http_mini/notes.org"))

;; == markdown-mode settings
(require 'markdown-mode)
(add-to-list 'auto-mode-alist '("\\.md$" . markdown-mode))

;; haskell mode indent
(add-hook 'haskell-mode-hook 'haskell-indent-mode)

;; -- various minor mode settings -- begin

;; vertical bar cursor by default
(set-default 'cursor-type 'bar)

;; don't stop blinking at all
(setq blink-cursor-blinks 0)

;; override insert key to change cursor in overwrite mode
(defvar cursor-mode-status 0)
(global-set-key (kbd "<insert>")
		(lambda () (interactive)
		  (cond ((eq cursor-mode-status 0)
			 (setq cursor-type 'box)
			 (overwrite-mode (setq cursor-mode-status 1)))
			(t
			  (setq cursor-type 'bar)
			  (overwrite-mode (setq cursor-mode-status 0))))))

;; tabs and indentation
(setq-default indent-tabs-mode nil)
(setq-default tab-width 4)
(setq-default show-trailing-whitespace)
(setq-default show-hard-spaces)
;;(show-ws-toggle-show-trailing-whitespace)

;; show the column number in status bar
(column-number-mode t)
;; hide the toolbar (those big icons with diskette)
(tool-bar-mode -1)
;; switch off screen blinking when reaching the end of file
(setq visible-bell nil)
;; I don't like scrollbar, and you?
(scroll-bar-mode -1)
;; enable to use X clipboard with emacs
(setq x-select-enable-clipboard t)
;; don't create backup files
(setq make-backup-files nil)

;; -- various minor mode settings -- end

;; this is needed for client-server emacs workflow
(server-start)

(set-face-font 'default "monofur")
