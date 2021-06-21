"use strict";

$(function () {
	/**
	 * Open confirm modal and change form action
	 */
	$(document).on("click", ".confirm-modal-btn", function () {
		let button = $(this),
			modal = button.data("modal"),
			action = button.data("action");

		$("#" + modal + "Form").prop("action", action);
		$("#" + modal + "Modal").modal("show");
	});

	/**
	 * Open get payment modal, change form action and price
	 * !Price fetching from database
	 */
	$(document).on("click", ".get-payment-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			price = button.data("price");

		$("#paymentForm").prop("action", action);
		$("#paymentModal").modal("show");
		$("#inpPrice").val(price);
	});

	/**
	 * Open create payment modal and change form action
	 */
	$(document).on("click", ".create-payment-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			modal = $("#createPaymentModal");

		modal.find("#createPaymentForm").prop("action", action);
		modal.modal("show");
	});

	/**
	 * Open delete payment modal and change form action
	 */
	$(document).on("click", ".delete-payment-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			customer = button.data("customer"),
			payment = button.data("payment"),
			modal = $("#deletePaymentModal");

		$("#inpDeletePaymentModalCustomer").val(customer);
		$("#inpDeletePaymentModalPayment").val(payment);
		$("#deletePaymentForm").prop("action", action);
		modal.modal("show");
	});

	/**
	 * Open edit payment price modal, change form action and price
	 */
	$(document).on("click", ".edit-payment-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			price = button.data("price");

		$("#editPaymentPriceForm").prop("action", action);
		$("#editPaymentPriceModal").modal("show");
		$("#inpEditPaymentModalEditPrice").val(price);
	});

	/**
	 * Open edit subscription price modal, change form action and price
	 */
	$(document).on("click", ".edit-subscription-price-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			price = button.data("price"),
			customer = button.data("customer"),
			service = button.data("service");

		$("#editSubscriptionPriceForm").prop("action", action);
		$("#editSubscriptionPriceModal").modal("show");
		$("#inpEditSubPriceModalEditPrice").val(price);
		$("#inpEditSubPriceModalCustomer").val(customer);
		$("#inpEditSubPriceModalService").val(service);
	});

	/**
	 * Open subscription cancellation modal, change form inputs
	 */
	$(document).on("click", ".cancel-subscription-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			customer = button.data("customer"),
			service = button.data("service");

		$("#cancelSubscriptionForm").prop("action", action);
		$("#cancelSubscriptionModal").modal("show");
		$("#inpCancelSubscriptionModalCustomer").val(customer);
		$("#inpCancelSubscriptionModalService").val(service);
	});

	/**
	 * Open edit reference modal, change form values
	 */
	$(document).on("click", ".edit-reference-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			status = button.data("status"),
			row = button.parents("tr");

		$("#editReferenceForm").prop("action", action);
		$("#editReferenceModal").modal("show");

		$("#inpEditReferenceModalReference").val(row.find(".reference-subscription").text().trim());
		$("#inpEditReferenceModalReferenced").val(row.find(".referenced-subscription").text().trim());
		$("#slcEditReferenceModalStatus").val(status).trigger("change");
	});

	/**
	 * Open subscription freeze modal, change form inputs
	 */
	$(document).on("click", ".freeze-subscription-modal-btn", function () {
		let button = $(this),
			action = button.data("action"),
			customer = button.data("customer"),
			service = button.data("service");

		$("#freezeSubscriptionForm").prop("action", action);
		$("#freezeSubscriptionModal").modal("show");
		$("#inpFreezeSubscriptionModalCustomer").val(customer);
		$("#inpFreezeSubscriptionModalService").val(service);
	});
});
