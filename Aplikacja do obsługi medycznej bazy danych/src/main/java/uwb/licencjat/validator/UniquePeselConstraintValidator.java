package uwb.licencjat.validator;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;
import uwb.licencjat.repository.UserRepository;
import uwb.licencjat.service.UserService;

import javax.validation.ConstraintValidator;
import javax.validation.ConstraintValidatorContext;

@Component
public class UniquePeselConstraintValidator implements ConstraintValidator<UniquePesel, String> {

    private UserRepository userRepository;

    @Autowired
    public UniquePeselConstraintValidator(UserRepository userRepository) {
        this.userRepository = userRepository;
    }

    @Override
    public void initialize(UniquePesel constraintAnnotation) {
        ConstraintValidator.super.initialize(constraintAnnotation);
    }

    @Override
    public boolean isValid(String pesel, ConstraintValidatorContext constraintValidatorContext) {
        return !userRepository.existsByPeselIgnoreCase(pesel);
    }
}
