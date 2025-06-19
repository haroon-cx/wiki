// assets/main.js

jQuery(document).ready(function ($) {
  // Open the report popup when clicking the Report button
  $(".report-button").click(function () {
    var questionId = $(this).data("question-id");
    $("#report-question-popup-" + questionId).fadeIn();
  });

  // Close the report popup when clicking the Cancel button
  $(".cancel-button").click(function () {
    $(this).closest(".report-popup").fadeOut();
  });
  // 👍 Like Button (Only updates like_count in the database)
  $(".like-btn").on("click", function () {
    const answerId = $(this).data("answer-id");

    // Toggle the 'liked' state for the like button
    $(this).toggleClass("liked");

    // Send AJAX request to increment the like count in the database
    $.post(
      "/wp-admin/admin-ajax.php",
      {
        action: "agqa_like_answer",
        answer_id: answerId,
        nonce: agqa_ajax.nonce,
      },
      function (data) {
        if (data.success) {
          // Like is successfully incremented in the database, no need to update frontend count
          console.log("Like added");
        }
      },
      "json"
    );
  });

  // 👎 Dislike Button (Only updates dislike_count in the database)
  $(".dislike-btn").on("click", function () {
    const answerId = $(this).data("answer-id");

    // Toggle the 'disliked' state for the dislike button
    $(this).toggleClass("disliked");

    // Send AJAX request to increment the dislike count in the database
    $.post(
      "/wp-admin/admin-ajax.php",
      {
        action: "agqa_dislike_answer",
        answer_id: answerId,
        nonce: agqa_ajax.nonce,
      },
      function (data) {
        if (data.success) {
          // Dislike is successfully incremented in the database
          console.log("Dislike added");
        }
      },
      "json"
    );
  });

  // 📋 Copy Button with Clipboard API + Fallback
  $(".copy-btn").on("click", function () {
    const button = this;
    const textToCopy = $(this).attr("data"); // using `data` attribute (not `data-*`)

    // Toggle the 'copied' state for the copy button
    $(this).toggleClass("copied");

    if (navigator.clipboard && window.isSecureContext) {
      // Modern clipboard
      navigator.clipboard
        .writeText(textToCopy)
        .then(() => {
          button.textContent = "✅ Copied!";
          setTimeout(() => {
            button.textContent = "📋 Copy";
            $(button).removeClass("copied"); // Reset the copied state after 2 seconds
          }, 2000);
        })
        .catch((err) => {
          console.error("Copy failed:", err);
          alert("Copy failed.");
        });
    } else {
      // Fallback for insecure context or unsupported browser
      const textarea = document.createElement("textarea");
      textarea.value = textToCopy;
      textarea.style.position = "fixed"; // Prevent scroll jump
      textarea.style.left = "-9999px";
      document.body.appendChild(textarea);
      textarea.focus();
      textarea.select();

      try {
        const successful = document.execCommand("copy");
        if (successful) {
          button.textContent = "✅ Copied!";
          setTimeout(() => {
            button.textContent = "📋 Copy";
            $(button).removeClass("copied"); // Reset the copied state after 2 seconds
          }, 2000);
        } else {
          alert("Copy failed.");
        }
      } catch (err) {
        alert("Copy error.");
      }

      document.body.removeChild(textarea);
    }
  });

  const nonce = agqa_ajax.nonce;
  function fetchCategories() {
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_get_categories", nonce },
      function (res) {
        if (res.success) {
          const catSelect = $("#agqa-admin-cat-select")
            .empty()
            .append('<option value="">Select Category</option>');
          res.data.forEach((c) => {
            catSelect.append(`<option value="${c.id}">${c.name}</option>`);
          });
        }
      }
    );
  }

  function truncateWords(text, limit = 20) {
    const words = text.split(/\s+/);
    if (words.length > limit) {
      return words.slice(0, limit).join(" ") + "...";
    }
    return text;
  }

  function fetchPosts() {
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_get_posts", nonce },
      function (res) {
        if (res.success) {
          const list = $("#agqa-post-list").empty();
          const postSelect = $("#agqa-admin-post-select")
            .empty()
            .append('<option value="">Select Post</option>');
          res.data.forEach((p) => {
            const truncatedContent = truncateWords(p.content, 20);
            const box = $(`<div class="agqa-post-box">
                       <div class="agqa-post-title">
                       <h4>${p.title}</h4>
                        <p>${truncatedContent}</p>
                        </div>
                        <div class="agqa-post-image">
                        <img src="${p.image_url}" alt="">
                        </div>
                    </div>`);
            box.click(() => {
              window.location.href = `/post/?id=${p.id}`;
            });
            list.append(box);
            postSelect.append(`<option value="${p.id}">${p.title}</option>`);
          });
        }
      }
    );
  }

  function fetchComplaints() {
    if (!agqa_ajax.is_admin) return;
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_get_complaints", nonce },
      function (res) {
        const wrap = $("#agqa-admin-complaints").empty();
        if (res.success) {
          res.data.forEach((c) => {
            const card = $(`<div class="agqa-complaint-box">
                        <p><strong>Answer:</strong> ${c.answer_text}</p>
                        <p><strong>Reason:</strong> ${c.reason}</p>
                        <textarea placeholder="Admin Note"></textarea>
                        <button class="approve-btn">✅ Approve</button>
                        <button class="reject-btn">❌ Reject</button>
                    </div>`);
            card
              .find(".approve-btn")
              .click(() =>
                moderateComplaint(c.id, "approved", card.find("textarea").val())
              );
            card
              .find(".reject-btn")
              .click(() =>
                moderateComplaint(c.id, "rejected", card.find("textarea").val())
              );
            wrap.append(card);
          });
        }
      }
    );
  }

  function moderateComplaint(id, decision, note) {
    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_moderate_complaint",
        complaint_id: id,
        decision,
        note,
        nonce,
      },
      function (res) {
        if (res.success) {
          alert("Complaint processed");
          fetchComplaints();
        }
      }
    );
  }

  let agqaSearchXHR = null;

  $("#agqa-search-input").on("input", function () {
    const term = $(this).val().trim();

    // Agar input 2 se chhota ho to turant results clear kar do aur AJAX cancel karo
    if (term.length < 1) {
      if (agqaSearchXHR) {
        agqaSearchXHR.abort();
        agqaSearchXHR = null;
      }
      $("#agqa-search-results").empty();
      return;
    }

    // Purana AJAX request cancel kar do agar chal raha ho
    if (agqaSearchXHR) {
      agqaSearchXHR.abort();
    }

    agqaSearchXHR = $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_search_all",
        term: term,
        nonce: agqa_ajax.nonce,
      },
      function (res) {
        const box = $("#agqa-search-results").empty();
        if (res.success && res.data.length > 0) {
          res.data.forEach((row) => {
            box.append(`<div class="agqa-search-result" data-question-id="${
              row.question_id
            }">
                    <strong>${row.type.toUpperCase()}</strong> in <em>${
              row.post_title
            }</em>:<br>
                    ${row.content}
                </div>`);
          });
        } else {
          box.append(
            '<div class="agqa-search-no-results">No results found.</div>'
          );
        }
      }
    ).always(function () {
      agqaSearchXHR = null; // AJAX request khatam hone ke baad reset kar do
    });
  });

  $("#agqa-search-results").on("click", ".agqa-search-result", function () {
    const qid = $(this).data("question-id");
    if (qid) {
      window.location.href = "/question/?id=" + qid;
    }
  });

  $("#agqa-submit-answer").click(function () {
    const question_id = $("#agqa-answer-form").data("question-id");
    const content = $("#agqa-answer-text").val();
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_submit_answer", question_id, content, nonce },
      function (res) {
        if (res.success) {
          $("#agqa-answer-text").val("");
          loadAnswers(question_id);
        }
      }
    );
  });

  $("#agqa-submit-complaint").click(function () {
    const answer_id = $("#agqa-complaint-answer-list").val(); // Ensure the correct ID or element to get the answer ID
    const reason = $("input[name='complaint_reason']:checked").val(); // Get the selected radio button value
    const note = $('textarea[name="note"]').val(); // Optionally get the note entered by the user
    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_submit_complaint",
        answer_id,
        reason,
        note,
        nonce,
      },
      function (res) {
        if (res.success) {
          alert("Complaint submitted");
          $("#agqa-complaint-reason").val(""); // Clear the form field if necessary
        } else {
          alert("Error in submission");
        }
      }
    );
  });

  // $('#submit-report-question').click(function () {
  //     const question_id = $('#agqa-complaint-question-list').val(); // Question ID
  //     const reason = $("input[name='complaint_reason']:chec
  // ked").val(); // Get the selected reason
  //     const note = $('textarea[name="note"]').val(); // Get the note

  //     $.post(agqa_ajax.ajax_url, {
  //         action: 'agqa_submit_question_complaint',
  //         question_id,
  //         reason,
  //         note,
  //         nonce
  //     }, function (res) {
  //         if (res.success) {
  //             alert('Complaint submitted');
  //         } else {
  //             alert('Error in submission');
  //         }
  //     });
  // });

  $(document).on("click", ".report-question-button", function () {
    const question_id = $(this).data("question-id"); // Get question ID dynamically from the button's data attribute
    $("#agqa-complaint-question-list").val(question_id); // Set the question ID in the hidden input field
    $("#report-question-popup-" + question_id).fadeIn(); // Show the report question popup
  });

  $(document).on("click", "#submit-report-question", function (e) {
    e.preventDefault(); // Prevent the default form submission

    const question_id = $("#agqa-complaint-question-list").val(); // Get the question ID from the hidden input field
    const reason = $("input[name='complaint_reason']:checked").val(); // Get selected reason
    const note = $("textarea[name='note']").val(); // Get the additional note entered by the user
    // Check if all required fields are filled
    if (!question_id || !reason) {
      alert("Please select a reason and provide a valid question ID.");
      return; // Prevent submission if required fields are missing
    }

    // Make the AJAX request to submit the complaint
    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_submit_question_complaint", // Action for the backend
        question_id: question_id, // Pass the correct question ID
        reason: reason,
        note: note,
        nonce: agqa_ajax.nonce, // Include nonce for security
      },
      function (res) {
        if (res.success) {
          alert("Complaint submitted successfully");
          $("#report-question-popup-" + question_id).hide(); // Close the popup on success
        } else {
          alert("Error in submission");
        }
      }
    );
  });

  $("#agqa-admin-add-cat").click(function () {
    const name = $("#agqa-admin-cat-name").val();
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_add_category", name, nonce },
      function () {
        $("#agqa-admin-cat-name").val("");
        fetchCategories();
      }
    );
  });

  $("#agqa-admin-add-post").click(function () {
    const category_id = $("#agqa-admin-cat-select").val();
    const title = $("#agqa-admin-post-title").val();
    const content = $("#agqa-admin-post-content").val();
    const image_url = $("#agqa-admin-post-image").val();
    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_add_post",
        category_id,
        title,
        content,
        image_url,
        nonce,
      },
      function (res) {
        console.log(res.data.message);
        if (res.data.status === "success") {
          alert(res.data.message);
          $("div#agqa-add-game-modal").hide();
        }
        $(
          "#agqa-admin-post-title, #agqa-admin-post-content, #agqa-admin-post-image"
        ).val("");
        fetchPosts();
      }
    );
  });

  $("#agqa-admin-add-question").click(function () {
    const post_id = $("#agqa-admin-post-select").val();
    const question = $("#agqa-admin-question").val();
    $.post(
      agqa_ajax.ajax_url,
      { action: "agqa_add_question", post_id, question, nonce },
      function () {
        $("#agqa-admin-question").val("");
      }
    );
  });

  $("#agqa-admin-post-image").on("click", function (e) {
    e.preventDefault();

    let image_frame = wp.media({
      title: "Select or Upload Image",
      button: {
        text: "Use this image",
      },
      multiple: false,
    });

    image_frame.on("select", function () {
      let attachment = image_frame.state().get("selection").first().toJSON();
      $("#agqa-admin-post-image").val(attachment.url);
    });

    image_frame.open();
  });
  // 🧠 Handle edit, hide, show buttons via class (event delegation)

  $(document).on("click", ".agqa-edit-game-btn", function () {
    const gameContainer = $(this).closest(".agqa-game-container");
    editGame(gameContainer); // pass container instead of button
  });

  $(document).on("click", ".agqa-hide-game-btn", function () {
    const gameContainer = $(this).closest(".agqa-game-container");
    toggleGameVisibility(gameContainer, "hide");
  });

  $(document).on("click", ".agqa-show-game-btn", function () {
    const gameContainer = $(this).closest(".agqa-game-container");
    toggleGameVisibility(gameContainer, "show");
  });
  $("#agqa-save-game-button").on("click", function () {
    const title = $("#agqa-edit-game-title").val();
    const image = $("#agqa-admin-post-image").val();
    const description = $("#agqa-edit-game-description").val();
    const urlParams = new URLSearchParams(window.location.search);
    const game_id = urlParams.get("id");

    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_edit_game_full",
        nonce: agqa_ajax.nonce,
        game_id: game_id,
        new_title: title,
        new_image: image,
        new_description: description,
      },
      function (res) {
        console.log(res.data.message);
        if (res.success) {
          alert("✅ Game updated successfully!");
          location.reload(); // Refresh page to show updated content
        } else {
          alert("❌ Failed to update game. Please try again.");
        }
      }
    );
  });

  function editGame(gameContainer) {
    $("#agqa-edit-game-modal").show();
  }

  function toggleGameVisibility(gameContainer, status) {
    const urlParams = new URLSearchParams(window.location.search);
    const game_id = urlParams.get("id");

    jQuery.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_toggle_game_visibility",
        nonce: agqa_ajax.nonce,
        game_id: game_id,
        status: status,
      },
      function (res) {
        if (res.success) {
          alert(
            "Game is now " + (status === "hide" ? "hidden" : "visible") + "."
          );
          location.reload(); // Refresh page to show updated content
        } else {
          alert("Failed to update visibility.");
        }
      }
    );
  }

  jQuery(document).on("change", ".agqa-status-dropdown", function () {
    const select = jQuery(this);
    const newStatus = select.val();
    const gameId = select.data("game-id");

    jQuery.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_update_status",
        nonce: agqa_ajax.nonce,
        game_id: gameId,
        status: newStatus,
      },
      function (res) {
        if (res.success) {
          alert("Status updated successfully!");
          window.location.href = `/post/?id=${gameId}`;
        } else {
          alert("Failed to update status.");
        }
      }
    );
  });

  // Edit question
  // Open edit modal with current question text
  jQuery(document).on("click", ".agqa-edit-question-btn", function () {
    const questionId = jQuery(this).data("question-id");
    const questionText = jQuery(this).closest("li").find("a").text().trim();

    jQuery("#agqa-edit-question-id").val(questionId);
    jQuery("#agqa-edit-question-text").val(questionText);
    jQuery("#agqa-edit-question-modal").show();
  });

  // Save updated question via AJAX
  jQuery("#agqa-save-question-btn").on("click", function () {
    const questionId = jQuery("#agqa-edit-question-id").val();
    const newQuestion = jQuery("#agqa-edit-question-text").val().trim();

    if (!newQuestion) {
      alert("Question text cannot be empty.");
      return;
    }

    jQuery.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_edit_question",
        nonce: agqa_ajax.nonce,
        question_id: questionId,
        new_question: newQuestion,
      },
      function (res) {
        if (res.success) {
          alert("Question updated successfully.");
          location.reload();
        } else {
          alert("Failed to update question.");
        }
      }
    );
  });

  // Toggle status menu visibility
  jQuery(document).on("click", ".agqa-status-toggle", function (e) {
    e.stopPropagation();
    const menu = jQuery(this).siblings(".agqa-status-menu");
    jQuery(".agqa-status-menu").not(menu).hide();
    menu.toggle();
  });

  // Hide status menu when clicking outside
  jQuery(document).on("click", function () {
    jQuery(".agqa-status-menu").hide();
  });

  // Handle status item click
  jQuery(document).on("click", ".agqa-status-item", function () {
    const status = jQuery(this).data("status");
    const questionId = jQuery(this).parent().data("question-id");

    // AJAX call to update status
    jQuery.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_update_question_status",
        nonce: agqa_ajax.nonce,
        question_id: questionId,
        status: status,
      },
      function (res) {
        if (res.success) {
          alert(`Status updated to ${status}`);
          location.reload();
        } else {
          alert("Failed to update status");
        }
      }
    );

    jQuery(this).parent().hide();
  });

  $(document).on("click", ".agqa-toggle-visibility-btn", function () {
    const btn = $(this);
    const questionId = btn.data("question-id");
    const currentTitle = btn.attr("title").toLowerCase();
    const action = currentTitle.includes("hide") ? "hide" : "show";

    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_toggle_question_visibility",
        nonce: agqa_ajax.nonce,
        question_id: questionId,
        status: action,
      },
      function (res) {
        if (res.success) {
          alert(`Question is now ${action === "hide" ? "hidden" : "visible"}.`);
          location.reload();
        } else {
          alert("Failed to update visibility.");
        }
      }
    );
  });

  // Toggle dropdown menu on button click
  $(document).on("click", ".agqa-dropdown-toggle", function (e) {
    e.stopPropagation(); // Prevent the event from bubbling up and closing immediately
    const menu = $(this).siblings(".agqa-dropdown-menu");
    $(".agqa-dropdown-menu").not(menu).hide(); // Hide other dropdowns if open
    menu.toggle(); // Toggle this dropdown
  });

  // Close dropdown if clicking outside
  $(document).on("click", function () {
    $(".agqa-dropdown-menu").hide();
  });

  $(document).on("click", ".agqa-dropdown-item", function () {
    const action = $(this).data("action"); // dropdown item ka action
    const answerId = $(this).parent().find('input[name="answer_id"]').val();
    if (!action || !answerId) {
      alert("Invalid action or answer ID");
      return;
    }

    $.post(
      agqa_ajax.ajax_url,
      {
        action: "agqa_dropdown_action",
        nonce: agqa_ajax.nonce,
        answer_id: answerId,
        dropdown_action: action,
      },
      function (res) {
        if (res.success) {
          alert('Action "' + action + '" completed successfully!');
          location.reload();
        } else {
          alert("Action failed: " + (res.data || "Unknown error"));
        }
      }
    );
  });

  fetchCategories();
  fetchPosts();
  fetchComplaints();
});
