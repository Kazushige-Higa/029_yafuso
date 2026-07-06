document.addEventListener("DOMContentLoaded", function () {
  var tableOfContentsList = document.querySelectorAll(
    '[data-collapsable="true"] .table_of_contents',
  );

  var normalizeAnchors = function (anchorValue) {
    if (!anchorValue) {
      return "h2,h3,h4,h5,h6";
    }

    var selectors = anchorValue
      .split(",")
      .map(function (selector) {
        return selector.trim().toLowerCase();
      })
      .filter(function (selector) {
        return /^h[1-6]$/.test(selector);
      });

    return selectors.length ? selectors.join(",") : "h2,h3,h4,h5,h6";
  };

  var createSlug = function (text) {
    return text
      .toLowerCase()
      .trim()
      .replace(/\s+/g, "-")
      .replace(/[^a-z0-9\-_]/g, "")
      .replace(/-+/g, "-")
      .replace(/^-|-$/g, "");
  };

  var assignHeadingId = function (heading, index) {
    var currentId = heading.id ? heading.id.trim() : "";
    var currentIdOwner = currentId ? document.getElementById(currentId) : null;
    if (currentId && (!currentIdOwner || currentIdOwner === heading)) {
      return currentId;
    }

    var headingText = heading.textContent ? heading.textContent.trim() : "";
    var baseId = createSlug(headingText) || "toc-heading-" + (index + 1);
    var candidateId = baseId;
    var suffix = 2;

    while (document.getElementById(candidateId)) {
      candidateId = baseId + "-" + suffix;
      suffix += 1;
    }

    heading.id = candidateId;
    return candidateId;
  };

  var buildTocTree = function (headings) {
    var root = {
      level: 0,
      children: [],
    };
    var stack = [root];

    headings.forEach(function (heading, headingIndex) {
      var headingLevel = Number(heading.tagName.replace("H", ""));
      if (!headingLevel) {
        return;
      }

      while (
        stack.length > 1 &&
        headingLevel <= stack[stack.length - 1].level
      ) {
        stack.pop();
      }

      var node = {
        level: headingLevel,
        id: assignHeadingId(heading, headingIndex),
        label: heading.textContent ? heading.textContent.trim() : "",
        children: [],
      };

      stack[stack.length - 1].children.push(node);
      stack.push(node);
    });

    return root.children;
  };

  var renderTocList = function (nodes) {
    var ol = document.createElement("ol");

    nodes.forEach(function (node) {
      var li = document.createElement("li");
      var link = document.createElement("a");

      link.href = "#" + node.id;
      link.textContent = node.label;
      li.appendChild(link);

      if (node.children.length) {
        li.appendChild(renderTocList(node.children));
      }

      ol.appendChild(li);
    });

    return ol;
  };

  tableOfContentsList.forEach(function (tableOfContents, index) {
    var icon = tableOfContents.querySelector(".table_of_contents_icon");
    var body = tableOfContents.querySelector(".table_of_contents_body");
    var tocWrapper = tableOfContents.parentElement;
    var headingSelector = normalizeAnchors(
      tocWrapper ? tocWrapper.getAttribute("data-anchors") : "",
    );
    var contentRoot =
      (tocWrapper && tocWrapper.closest("#countPost")) || document;
    var headings = Array.from(
      contentRoot.querySelectorAll(headingSelector),
    ).filter(function (heading) {
      return !heading.closest(".table_of_contents");
    });
    var tocTree = buildTocTree(headings);

    if (!icon || !body) {
      return;
    }

    body.innerHTML = "";
    body.appendChild(renderTocList(tocTree));

    icon.setAttribute("role", "button");
    icon.setAttribute("tabindex", "0");

    if (!body.id) {
      body.id = "toc-body-" + (index + 1);
    }

    icon.setAttribute("aria-controls", body.id);
    icon.setAttribute("aria-expanded", "true");
    body.setAttribute("aria-hidden", "false");

    var toggleBody = function () {
      var isExpanded = icon.getAttribute("aria-expanded") === "true";
      var nextExpanded = !isExpanded;

      icon.setAttribute("aria-expanded", String(nextExpanded));
      body.setAttribute("aria-hidden", String(!nextExpanded));
      tableOfContents.classList.toggle("is-collapsed", !nextExpanded);
    };

    icon.addEventListener("click", toggleBody);
    icon.addEventListener("keydown", function (event) {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        toggleBody();
      }
    });
  });

  var fallbackCopyText = function (text) {
    return new Promise(function (resolve, reject) {
      try {
        var textarea = document.createElement("textarea");
        textarea.value = text;
        textarea.setAttribute("readonly", "");
        textarea.style.position = "fixed";
        textarea.style.top = "-9999px";
        textarea.style.left = "-9999px";
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();

        var copied = document.execCommand("copy");
        document.body.removeChild(textarea);

        if (copied) {
          resolve();
          return;
        }

        reject(new Error("Copy command failed"));
      } catch (error) {
        reject(error);
      }
    });
  };

  var copyTextToClipboard = function (text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text);
    }
    return fallbackCopyText(text);
  };

  var setupCodeCopyButtons = function () {
    var codeWrappers = document.querySelectorAll("#countPost div[data-filename]");

    codeWrappers.forEach(function (wrapper) {
      if (wrapper.querySelector(".code-copy-button")) {
        return;
      }

      var code = wrapper.querySelector("pre code");
      if (!code) {
        return;
      }

      var button = document.createElement("button");
      button.type = "button";
      button.className = "code-copy-button";
      button.setAttribute("aria-label", "コードをコピー");
      button.setAttribute("title", "コードをコピー");
      button.style.margin = "0.5em 0.5em 0px auto";
      button.style.width = "2.2em";
      button.style.height = "2.2em";
      button.style.padding = "0";
      button.style.border = "1px solid #cbd5e1";
      button.style.borderRadius = "999px";
      button.style.background = "#ffffff";
      button.style.color = "#334155";
      button.style.fontSize = "0.9em";
      button.style.lineHeight = "1";
      button.style.cursor = "pointer";
      button.style.transition = "0.2s";
      button.style.display = "inline-flex";
      button.style.alignItems = "center";
      button.style.justifyContent = "center";
      button.style.position = "absolute";
      button.style.top = "0";
      button.style.right = "0";

      var setCopyIconState = function (state) {
        if (state === "success") {
          button.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i>';
          button.setAttribute("aria-label", "コピー完了");
          button.setAttribute("title", "コピー完了");
          button.style.background = "#e2f6ec";
          button.style.borderColor = "#86d1a8";
          button.style.color = "#166534";
          return;
        }

        if (state === "error") {
          button.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';
          button.setAttribute("aria-label", "コピー失敗");
          button.setAttribute("title", "コピー失敗");
          button.style.background = "#fee2e2";
          button.style.borderColor = "#fca5a5";
          button.style.color = "#991b1b";
          return;
        }

        button.innerHTML = '<i class="fas fa-copy" aria-hidden="true"></i>';
        button.setAttribute("aria-label", "コードをコピー");
        button.setAttribute("title", "コードをコピー");
        button.style.background = "#ffffff";
        button.style.borderColor = "#cbd5e1";
        button.style.color = "#334155";
      };

      setCopyIconState("default");

      var pre = wrapper.querySelector("pre");
      if (pre) {
        wrapper.insertBefore(button, pre);
      } else {
        wrapper.appendChild(button);
      }

      button.addEventListener("click", function () {
        var codeText = code.textContent || "";

        if (!codeText.trim()) {
          return;
        }

        button.disabled = true;
        copyTextToClipboard(codeText)
          .then(function () {
            setCopyIconState("success");
          })
          .catch(function () {
            setCopyIconState("error");
          })
          .finally(function () {
            window.setTimeout(function () {
              setCopyIconState("default");
              button.disabled = false;
            }, 1800);
          });
      });
    });
  };

  setupCodeCopyButtons();
});
