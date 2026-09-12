<form id="contactForm" novalidate>
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" maxlength="120" autocomplete="name" required>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="254" autocomplete="email" required>
    </div>

    <div class="form-group">
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" maxlength="500" required></textarea>
        <small id="messageCounter" class="form-meta">0/500 characters</small>
    </div>

    <!-- The retention panel and the send control share a row so the icon sits
         beside the options rather than on a line of its own. -->
    <div class="form-send-row">
        <div class="form-group form-retention-group">
            <p class="form-meta retention-label">Optional:</p>
            <div class="retention-inline-row">
                <label class="checkbox-row" for="autoDeleteConsent">
                    <input type="checkbox" id="autoDeleteConsent" name="autoDeleteConsent" value="1">
                    <span>Delete my message automatically after</span>
                </label>
                <div class="retention-picker">
                    <select id="deleteAfterDays" name="deleteAfterDays" disabled>
                        <option value="">Select days</option>
                        <option value="7">7 days</option>
                        <option value="14">14 days</option>
                        <option value="30">30 days</option>
                        <option value="60">60 days</option>
                        <option value="90">90 days</option>
                    </select>
                    <span>days.</span>
                </div>
            </div>
        </div>

        <button type="submit" class="contact-submit-btn" aria-label="Send message" title="Send message">
            <i class="fas fa-paper-plane" aria-hidden="true"></i>
        </button>
    </div>

    <small class="form-meta retention-note">The message are stored in database and will automatically delete in 90 days.</small>

    <div class="hp-field" aria-hidden="true">
        <label for="website">Website</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
    </div>

    <input type="hidden" id="submittedAt" name="submittedAt" value="">
</form>
<div id="formMessage"></div>
