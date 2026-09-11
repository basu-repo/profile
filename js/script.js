// Scroll to top on page load/refresh
window.onbeforeunload = function() {
    window.scrollTo(0, 0);
};

function openPrivacyModal(e) {
    if (e) e.preventDefault();
    const modal = document.getElementById('privacyModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closePrivacyModal(event) {
    const modal = document.getElementById('privacyModal');
    if (!modal) return;

    if (!event || event.target === modal || event.target.classList.contains('privacy-modal-close')) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePrivacyModal();
    }
});

// Scroll to Top Button
const scrollToTopBtn = document.getElementById('scrollToTop');
if (scrollToTopBtn) {
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    });

    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// Certificate Accordion
function toggleCert(id) {
    const item = document.getElementById(id);
    const isOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.cert-accordion-item').forEach(el => el.classList.remove('open'));
    // Open clicked if it was closed
    if (!isOpen) {
        item.classList.add('open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.js-cert-toggle').forEach(function(header) {
        header.addEventListener('click', function() {
            const targetId = this.getAttribute('data-cert-target');
            if (targetId) toggleCert(targetId);
        });
    });

    const privacyLink = document.getElementById('privacyPolicyLink');
    const privacyModal = document.getElementById('privacyModal');
    const privacyClose = document.getElementById('privacyModalClose');

    if (privacyLink) {
        privacyLink.addEventListener('click', openPrivacyModal);
    }

    if (privacyClose) {
        privacyClose.addEventListener('click', function() {
            closePrivacyModal();
        });
    }

    if (privacyModal) {
        privacyModal.addEventListener('click', function(event) {
            if (event.target === privacyModal) {
                closePrivacyModal(event);
            }
        });
    }
});

// Work Experience Accordion
document.addEventListener('DOMContentLoaded', function() {
    const experienceHeaders = document.querySelectorAll('.experience-header');
    experienceHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const parentItem = this.closest('.experience-item');
            if (parentItem) {
                parentItem.classList.toggle('open');
            }
        });
    });
});

// Smooth scrolling for navigation links
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-menu a');

    function scrollToTarget(href) {
        if (href === '#home') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        const target = document.querySelector(href);
        if (!target) return;

        const navbar = document.querySelector('.navbar');
        const navbarHeight = navbar ? navbar.offsetHeight : 0;
        const targetTop = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

        window.scrollTo({
            top: Math.max(targetTop, 0),
            behavior: 'smooth'
        });
    }
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                scrollToTarget(href);
            }
        });
    });
    
    // Update active nav link on scroll
    window.addEventListener('scroll', function() {
        let current = '';
        const sections = document.querySelectorAll('section[id]');
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
});

// Contact Form Handler
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    const submittedAtField = document.getElementById('submittedAt');
    const messageField = document.getElementById('message');
    const messageCounter = document.getElementById('messageCounter');
    const autoDeleteConsentField = document.getElementById('autoDeleteConsent');
    const deleteAfterDaysField = document.getElementById('deleteAfterDays');

    if (submittedAtField && !submittedAtField.value) {
        submittedAtField.value = String(Date.now());
    }

    if (contactForm && !contactForm.dataset.bound) {
        contactForm.addEventListener('submit', handleFormSubmit);
        contactForm.dataset.bound = '1';
    }

    if (messageField && messageCounter && !messageField.dataset.counterBound) {
        updateMessageCounter();
        ['input', 'keyup', 'change', 'paste'].forEach(function(evt) {
            messageField.addEventListener(evt, updateMessageCounter);
        });
        messageField.dataset.counterBound = '1';
    }

    if (autoDeleteConsentField && deleteAfterDaysField && !autoDeleteConsentField.dataset.retentionBound) {
        const syncRetentionState = function() {
            deleteAfterDaysField.disabled = !autoDeleteConsentField.checked;
            deleteAfterDaysField.required = autoDeleteConsentField.checked;

            if (!autoDeleteConsentField.checked) {
                deleteAfterDaysField.value = '';
            }
        };

        autoDeleteConsentField.addEventListener('change', syncRetentionState);
        syncRetentionState();
        autoDeleteConsentField.dataset.retentionBound = '1';
    }

    ensureFormLoader();
}

document.addEventListener('DOMContentLoaded', initContactForm);
initContactForm();

function handleFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) return;

    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    const website = document.getElementById('website') ? document.getElementById('website').value.trim() : '';
    const submittedAt = document.getElementById('submittedAt') ? document.getElementById('submittedAt').value.trim() : '';
    const autoDeleteConsent = document.getElementById('autoDeleteConsent') ? document.getElementById('autoDeleteConsent').checked : false;
    const deleteAfterDays = document.getElementById('deleteAfterDays') ? document.getElementById('deleteAfterDays').value.trim() : '';
    const allowedRetentionDays = ['7', '14', '30', '60', '90'];

    if (!name || !email || !message) {
        showMessage('Please fill in all fields', 'error');
        return;
    }

    if (!isValidEmail(email)) {
        showMessage('Please enter a valid email address', 'error');
        return;
    }

    if (message.length > 500) {
        showMessage('Please keep your message within 500 characters.', 'error');
        return;
    }

    if (website !== '') {
        showMessage('Unable to submit form. Please try again.', 'error');
        return;
    }

    if (!submittedAt) {
        showMessage('Please refresh and try again.', 'error');
        return;
    }

    if (autoDeleteConsent && !allowedRetentionDays.includes(deleteAfterDays)) {
        showMessage('Please choose after how many days your message should be deleted.', 'error');
        return;
    }

    // Disable button while sending
    submitBtn.disabled = true;
    setSubmitButtonState(submitBtn, true);
    showFormLoader('Saving your message...');

    fetch('/contact/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, message, website, submittedAt, autoDeleteConsent, deleteAfterDays })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Your message has been saved successfully.', 'success');
            form.reset();
            if (document.getElementById('submittedAt')) {
                document.getElementById('submittedAt').value = String(Date.now());
            }
            updateMessageCounter();
        } else {
            showMessage(data.message || 'Something went wrong. Please try again.', 'error');
        }
    })
    .catch(() => {
        showMessage('Unable to save your message right now. Please try again later.', 'error');
    })
    .finally(() => {
        hideFormLoader();
        submitBtn.disabled = false;
        setSubmitButtonState(submitBtn, false);
    });
}

function showMessage(text, type) {
    const formMessage = document.getElementById('formMessage');
    if (!formMessage) return;

    formMessage.textContent = text;
    formMessage.className = type;
    formMessage.style.display = '';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        formMessage.className = '';
        formMessage.style.display = 'none';
    }, 5000);
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function updateMessageCounter() {
    const messageField = document.getElementById('message');
    const messageCounter = document.getElementById('messageCounter');
    if (!messageField || !messageCounter) return;

    const charCount = messageField.value.length;
    messageCounter.textContent = charCount + '/500 characters';
    messageCounter.classList.toggle('limit-exceeded', charCount > 500);
}

function ensureFormLoader() {
    const form = document.getElementById('contactForm');
    if (!form) return;

    if (!document.getElementById('formLoader')) {
        const loader = document.createElement('div');
        loader.id = 'formLoader';
        loader.className = 'form-loader';
        loader.setAttribute('aria-live', 'polite');
        loader.innerHTML = '<span class="form-loader-spinner" aria-hidden="true"></span><span id="formLoaderText">Sending...</span>';
        form.insertAdjacentElement('afterend', loader);
    }
}

function showFormLoader(text) {
    const loader = document.getElementById('formLoader');
    const label = document.getElementById('formLoaderText');
    if (!loader) return;
    if (label && text) label.textContent = text;
    loader.classList.add('active');
}

function hideFormLoader() {
    const loader = document.getElementById('formLoader');
    if (!loader) return;
    loader.classList.remove('active');
}

function setSubmitButtonState(button, isSaving) {
    if (!button) return;

    if (isSaving) {
        button.textContent = 'Saving...';
        return;
    }

    button.innerHTML = '<i class="fas fa-paper-plane" aria-hidden="true"></i>';
}

function normalizeWysiwygHtml(html) {
    const value = (html || '').trim();
    return value === '' || value === '<p><br></p>' ? '' : value;
}

function initFallbackWysiwyg(editor, surface, input) {
    surface.setAttribute('contenteditable', 'true');

    const syncEditor = function() {
        input.value = normalizeWysiwygHtml(surface.innerHTML);
    };

    editor.querySelectorAll('.ql-bold, .ql-italic, .ql-underline, .ql-clean, .ql-link, .ql-list').forEach(function(button) {
        button.addEventListener('click', function() {
            surface.focus();

            if (button.classList.contains('ql-bold')) {
                document.execCommand('bold', false);
            } else if (button.classList.contains('ql-italic')) {
                document.execCommand('italic', false);
            } else if (button.classList.contains('ql-underline')) {
                document.execCommand('underline', false);
            } else if (button.classList.contains('ql-clean')) {
                document.execCommand('removeFormat', false);
            } else if (button.classList.contains('ql-link')) {
                const url = window.prompt('Enter a URL', 'https://');
                if (url) {
                    document.execCommand('createLink', false, url);
                }
            } else if (button.classList.contains('ql-list')) {
                const isOrdered = button.getAttribute('value') === 'ordered';
                document.execCommand(isOrdered ? 'insertOrderedList' : 'insertUnorderedList', false);
            }

            syncEditor();
        });
    });

    surface.addEventListener('input', syncEditor);
    const form = editor.closest('form');
    if (form) {
        form.addEventListener('submit', syncEditor);
    }

    syncEditor();
}

function initWysiwygEditors(root) {
    const scope = root || document;

    scope.querySelectorAll('[data-wysiwyg]').forEach(function(editor) {
        const surface = editor.querySelector('[data-editor-surface]');
        const input = editor.querySelector('[data-editor-input]');
        if (!surface || !input || editor.dataset.bound) return;

        if (window.Quill) {
            const toolbar = editor.querySelector('[data-editor-toolbar]');
            const quill = new window.Quill(surface, {
                theme: 'snow',
                modules: {
                    toolbar: toolbar || true
                },
                formats: ['bold', 'italic', 'underline', 'list', 'link']
            });

            const syncEditor = function() {
                input.value = normalizeWysiwygHtml(quill.root.innerHTML);
            };

            quill.on('text-change', syncEditor);
            syncEditor();

            editor.__quill = quill;
            const form = editor.closest('form');
            if (form) {
                form.addEventListener('submit', syncEditor);
            }
        } else {
            editor.classList.add('admin-wysiwyg-fallback');
            initFallbackWysiwyg(editor, surface, input);
        }

        editor.dataset.bound = '1';
    });
}

function initAdminSectionToggles(root) {
    const scope = root || document;

    scope.querySelectorAll('.admin-content-section').forEach(function(section) {
        const toggle = section.querySelector('[data-section-toggle]');
        const body = section.querySelector('[data-section-body]');
        if (!toggle || !body || section.dataset.toggleBound) return;

        const setOpenState = function(isOpen) {
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            section.classList.toggle('is-open', isOpen);
            body.hidden = !isOpen;
        };

        setOpenState(false);

        toggle.addEventListener('click', function() {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            setOpenState(!isOpen);
        });

        section.dataset.toggleBound = '1';
    });
}

function initDateRangeControls(root) {
    const scope = root || document;

    scope.querySelectorAll('[data-current-toggle]').forEach(function(toggle) {
        if (toggle.dataset.dateBound) return;

        const item = toggle.closest('[data-repeatable-item]') || toggle.closest('form') || document;
        const endDateInput = item.querySelector('[data-end-date]');
        if (!endDateInput) return;

        const syncDateState = function() {
            endDateInput.disabled = toggle.checked;
        };

        toggle.addEventListener('change', syncDateState);
        syncDateState();
        toggle.dataset.dateBound = '1';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initWysiwygEditors(document);
    initAdminSectionToggles(document);
    initDateRangeControls(document);

    document.querySelectorAll('[data-repeatable]').forEach(function(section) {
        const addButton = section.querySelector('[data-add-repeatable]');
        const itemsContainer = section.querySelector('.admin-repeatable-items');
        const template = section.querySelector('template');

        if (addButton && itemsContainer && template && !section.dataset.bound) {
            addButton.addEventListener('click', function() {
                const nextIndex = Number(section.dataset.nextIndex || '0');
                const nextNumber = itemsContainer.querySelectorAll('[data-repeatable-item]').length + 1;
                const html = template.innerHTML
                    .replace(/__INDEX__/g, String(nextIndex))
                    .replace(/__NUMBER__/g, String(nextNumber));

                const wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                const newItem = wrapper.firstElementChild;
                if (newItem) {
                    itemsContainer.appendChild(newItem);
                    section.dataset.nextIndex = String(nextIndex + 1);
                    initWysiwygEditors(newItem);
                    initDateRangeControls(newItem);
                }
            });

            section.dataset.bound = '1';
        }

        section.addEventListener('click', function(event) {
            const button = event.target.closest('[data-remove-repeatable]');
            if (!button) return;

            const item = button.closest('[data-repeatable-item]');
            if (!item) return;

            const currentItems = itemsContainer.querySelectorAll('[data-repeatable-item]');
            if (currentItems.length <= 1) {
                item.querySelectorAll('input[type="text"], input[type="url"], input[type="email"], textarea, input[type="hidden"]').forEach(function(field) {
                    field.value = '';
                });
                item.querySelectorAll('[data-wysiwyg]').forEach(function(editor) {
                    if (editor.__quill) {
                        editor.__quill.setContents([]);
                    }
                });
                item.querySelectorAll('[data-editor-surface]').forEach(function(surface) {
                    if (!surface.classList.contains('ql-container')) {
                        surface.innerHTML = '';
                    }
                });
                item.querySelectorAll('input[type="file"]').forEach(function(field) {
                    field.value = '';
                });
                item.querySelectorAll('img.admin-image-preview').forEach(function(img) {
                    img.remove();
                });
                return;
            }

            item.remove();
        });
    });
});

// Confirm destructive admin actions. Declared via data-confirm rather than an
// inline onsubmit handler, which the site's CSP (script-src 'self') blocks.
document.addEventListener('submit', function(event) {
    const form = event.target.closest('[data-confirm]');
    if (!form) return;

    if (!window.confirm(form.getAttribute('data-confirm'))) {
        event.preventDefault();
    }
});
