<?php
/* Save / send-enquiry handler (PHP 8 safe, replaces deprecated FILTER_SANITIZE_STRING) */

if (isset($_POST['save'])) {
   if (!empty($user_id)) {
      $property_id = trim((string)($_POST['property_id'] ?? ''));
      if ($property_id !== '') {
         $verify_saved = $conn->prepare("SELECT id FROM `saved` WHERE property_id = ? AND user_id = ?");
         $verify_saved->execute([$property_id, $user_id]);

         if ($verify_saved->rowCount() > 0) {
            $remove_saved = $conn->prepare("DELETE FROM `saved` WHERE property_id = ? AND user_id = ?");
            $remove_saved->execute([$property_id, $user_id]);
            $success_msg[] = 'Removed from saved!';
         } else {
            $save_id = create_unique_id();
            $insert_saved = $conn->prepare("INSERT INTO `saved`(id, property_id, user_id) VALUES (?,?,?)");
            $insert_saved->execute([$save_id, $property_id, $user_id]);
            $success_msg[] = 'Listing saved!';
         }
      }
   } else {
      $warning_msg[] = 'Please log in first!';
   }
}

if (isset($_POST['send'])) {
   if (!empty($user_id)) {
      $property_id = trim((string)($_POST['property_id'] ?? ''));
      if ($property_id !== '') {

         $select_receiver = $conn->prepare("SELECT user_id FROM `property` WHERE id = ? LIMIT 1");
         $select_receiver->execute([$property_id]);
         $fetch_receiver = $select_receiver->fetch();
         $receiver = $fetch_receiver['user_id'] ?? '';

         if ($receiver === $user_id) {
            $warning_msg[] = 'You cannot enquire on your own listing.';
         } elseif ($receiver === '') {
            $warning_msg[] = 'Listing not found.';
         } else {
            $verify_request = $conn->prepare("SELECT id FROM `requests` WHERE property_id = ? AND sender = ? AND receiver = ?");
            $verify_request->execute([$property_id, $user_id, $receiver]);

            if ($verify_request->rowCount() > 0) {
               $warning_msg[] = 'Enquiry already sent for this property!';
            } else {
               $request_id = create_unique_id();
               $send_request = $conn->prepare("INSERT INTO `requests`(id, property_id, sender, receiver) VALUES (?,?,?,?)");
               $send_request->execute([$request_id, $property_id, $user_id, $receiver]);
               $success_msg[] = 'Enquiry sent successfully!';
            }
         }
      }
   } else {
      $warning_msg[] = 'Please log in first!';
   }
}
